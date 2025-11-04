 <?php
/**
 * JsonSQL
 *
 * This class provides a simple way to interact with JSON files as if they were a database.
 * It allows for basic CRUD operations (Create, Read, Update, Delete) on entities
 * within the JSON structure, as well as searching, sorting, aggregation, and
 * import/export functionalities (CSV, SQLite).
 *
 * The JSON file is expected to be an object where keys represent "entities" or "tables",
 * and the values are arrays of objects, where each object is a "row" or "item".
 * Each item is expected to have a unique 'id' field, although the `create` method
 * generates one if not provided.
 *
 * Example JSON structure:
 * {
 *   "users": [
 *     {"id": "...", "name": "...", "email": "..."},
 *     {"id": "...", "name": "...", "email": "..."}
 *   ],
 *   "products": [
 *     {"id": "...", "name": "...", "price": "..."},
 *     {"id": "...", "name": "...", "price": "..."}
 *   ]
 * }
 *
 * @package JsonSQL
 */

class JsonSQL
{
    private $filename;
    
    /**
     * Constructor for the JsonSQL class.
     * Initializes the JSON file path and creates the file if it doesn't exist.
     *
     * @param string $filename The path to the JSON file.
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
        if (!file_exists($this->filename)) {
            $this->createFile($this->filename);
        }
    }

    /**
     * Generates or verifies a password-compatible key pair.
     *
     * When $mode is 'generate' and $hash is omitted, a new random key is produced
     * together with its hashed representation.  
     * When $mode is 'verify', the supplied $input (plain key) is checked against
     * the previously stored $hash (base-64 encoded hash).
     *
     * @param string      $input  Plain-text key material.
     * @param string|null $hash   Base-64 encoded hash to verify against (used only in 'verify' mode).
     * @param string      $mode   'generate' | 'verify'
     * @return array|bool         Array ['key'=>plain, 'hash'=>encoded] on generate, bool on verify.
     */
    public function key($input,$hash=null,$mode='generate')
    {
        if($mode == 'generate' && (is_null($hash))){
            return $this->KeyGen($input);
        }elseif($mode == 'verify'){
            return $this->verifyKey($input,$hash);
        }
    }

    /**
     * Derives a 32-byte libsodium-compatible key from any input string.
     *
     * Uses BLAKE2b hashing (provided by libsodium) to turn an arbitrary-length
     * string into a fixed-size key that satisfies SODIUM_CRYPTO_SECRETBOX_KEYBYTES.
     *
     * @param string $input Any user-supplied string (pass-phrase, password, etc.).
     * @return string A 32-byte key ready for encryptFile()/decryptFile().
     */
    private function KeyGen(bool|string $input = false): array
    {
        // Fallback when libsodium is unavailable: use hash_hmac with SHA-256 and truncate to 32 bytes
        $input = $input ?: md5(uniqid());
        
        $data = password_hash($input, PASSWORD_DEFAULT);
        $data = base64_encode($data);
        return [
            'key'=>$input,
            'hash'=>$data
        ];
    }

    /**
     * Verifies that a given key is exactly 32 bytes long and suitable for libsodium.
     *
     * @param string $key The key to validate.
     * @return bool True if the key is 32 bytes, false otherwise.
     */
    private function verifyKey(string $key, $hash): bool
    {
        $hash = base64_decode($hash, true);
        return password_verify($key, $hash);
        
    }

    /**
     * Encrypts the JSON file using OpenSSL.
     *
     * @param string $filename The path to the JSON file.
     * @param string $key The encryption key.
     * @return string The encrypted data.
     * @throws Exception If encryption fails.
     */
    public function encryptFile(string $key): string
    {
        $data = file_get_contents($this->filename);
        if ($data === false) {
            throw new Exception("Failed to read file: $this->filename");
        }

        $cipher = 'aes-256-cbc';
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);

        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        if ($encrypted === false) {
            throw new Exception("Encryption failed.");
        }

        // Prepend IV to the encrypted data for decryption
        $encrypted = base64_encode($iv . $encrypted);
        $encrypted = gzcompress($this->filename.'::enc::'.$encrypted);
        file_put_contents('data.enc',$encrypted); // Save encrypted data to a new file
        return true;
    }

    /**
     * Decrypts the JSON file using OpenSSL.
     *
     * @param string $filename The path to the encrypted JSON file.
     * @param string $key The decryption key.
     * @return string The decrypted data.
     * @throws Exception If decryption fails.
     */
    public function decryptFile(string $key): string
    {
        $encryptedData = file_get_contents('data.enc');
        if ($encryptedData === false) {
            throw new Exception("Failed to read encrypted file");
        }
        $encryptedData = gzuncompress($encryptedData);
        $Data = explode('::enc::',$encryptedData);
        $this->filename = $Data[0];
        $encryptedData = $Data[1];

        $encryptedData = base64_decode($encryptedData);

        $cipher = 'aes-256-cbc';
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($encryptedData, 0, $ivlen);
        $encryptedPayload = substr($encryptedData, $ivlen);

        $decrypted = openssl_decrypt($encryptedPayload, $cipher, $key, 0, $iv);
        if ($decrypted === false) {
            throw new Exception("Decryption failed.");
        }
        if(!file_exists($this->filename)){
            $this->createFile($this->filename);
        }
        file_put_contents($this->filename,$decrypted);
        unlink('data.enc');
        return true;
    }

    /**
     * Generates password hashes or verifies password-hash pairs.
     * 
     * @param string $input The input string to hash or verify
     * @param string|null $hash The hash to verify against (only for verify mode)
     * @param string $mode 'hash' to generate hash, 'verify' to verify password
     * @return string|bool Hash string in hash mode, boolean in verify mode 
     */
    public function passwordHash($input,$hash=null,$mode ='hash')
    {
        $str = 'qwertyuiop[]asdfghjkl;\'\\zxcvbnm,./';
        if($mode == 'hash'){    
            return password_hash($str.$input.$str, PASSWORD_DEFAULT);
        }elseif($mode == 'verify'){
            return password_verify($str.$input.$str, $hash);
        }
    }
    
    /**
     * Creates a new JSON file with optional initial data.
     *
     * @param string $filename The path to the new JSON file.
     * @param array $initialData Optional initial data to write to the file.
     * @return bool True on success.
     * @throws Exception If the file already exists or creation fails.
     */
    public function createFile($filename, $initialData = [])
    {
        if (file_exists($filename)) {
            throw new Exception("File '$filename' already exists.");
        }

        $jsonData = json_encode($initialData, JSON_PRETTY_PRINT);
        if (file_put_contents($filename, $jsonData) === false) {
            throw new Exception("Failed to create file '$filename'.");
        }

        $this->filename = $filename; // Update the current filename
        return true;
    }
    
    /**
     * Loads data from the JSON file.
     *
     * @return array The decoded JSON data.
     */
    private function loadData()
    {
        $data = file_get_contents($this->filename);
        return json_decode($data, true);
    }
    
    /**
     * Saves data to the JSON file.
     *
     * @param array $data The data to encode and save.
     */
    private function saveData($data)
    {
        $data = $this->cleanData($data);
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->filename, $jsonData);
    }
    
    /**
     * Cleans data by trimming strings, converting special characters to HTML entities,
     * and removing script tags to prevent XSS.
     *
     * @param mixed $data The data to clean.
     * @return mixed The cleaned data.
     */
    private function cleanData($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'cleanData'], $data);
        }
        if (is_string($data)) {
            $data = trim($data);
            // Remove dangerous characters while preserving JSON structure
            $data = htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            // Prevent script injection
            $data = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $data);
            return $data;
        }
        return $data;
    }
    
    /**
     * Converts a given number of bytes into a human-readable format (Bytes, KB, MB, GB).
     *
     * @param int $bytes The number of bytes.
     * @return string The formatted size string.
     */
    private function convertSize($bytes) {
 
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2). ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2). ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2). ' KB';
        } else {
            $bytes = $bytes. ' Bytes';
        }
        return $bytes;
    }
    
    /**
     * Gets the size of the JSON file in a human-readable format.
     *
     * @return string The formatted file size.
     */
    public function getFileSize()
    {
        $bytes = filesize($this->filename);
        return $this->convertSize($bytes);
    }
    
    /**
     * Counts the number of rows (items) in a specified entity.
     *
     * @param string $entity The name of the entity (e.g., 'users', 'products').
     * @return int The number of rows in the entity, or 0 if the entity does not exist.
     */
    public function countRows($entity)
    {
        $data = $this->loadData();
    
        if (!isset($data[$entity])) {
            return 0;
        }
    
        return count($data[$entity]);
    }
    
    /**
     * Retrieves all items from a specified entity.
     *
     * @param string $entity The name of the entity.
     * @return array An array of all items in the entity, or an empty array if the entity does not exist.
     */
    public function getAll($entity)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            return [];
        }

        return $data[$entity];
    }
    
    /**
     * Retrieves a limited and optionally sorted subset of items from a specified entity.
     *
     * @param string $entity The name of the entity.
     * @param int $limit The maximum number of items to return.
     * @param int $offset The starting offset for retrieving items.
     * @param string|null $field Optional field to sort by.
     * @param string $order The sort order ('asc' for ascending, 'desc' for descending).
     * @return array An array of limited and sorted items.
     */
    public function getLimited($entity, $limit = 25, $offset = 0, $field = null, $order = 'asc')
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            return [];
        }

        $items = $data[$entity];

        // Optional sorting before slicing
        if ($field !== null) {
            usort($items, function($a, $b) use ($field, $order) {
                // Handle cases where the field might not exist in an item
                $aValue = isset($a[$field]) ? $a[$field] : null;
                $bValue = isset($b[$field]) ? $b[$field] : null;

                // Basic comparison, adjust for specific types if needed
                if ($aValue === $bValue) {
                    return 0;
                }

                if ($order === 'asc') {
                    return ($aValue < $bValue) ? -1 : 1;
                } else { // Descending order
                    return ($aValue > $bValue) ? -1 : 1;
                }
            });
        }

        // Apply offset and limit
        $result = array_slice($items, $offset, $limit);

        return $result;
    }
    
    /**
     * Retrieves a single item from an entity by its ID.
     *
     * @param string $entity The name of the entity.
     * @param string $id The ID of the item to retrieve.
     * @return array|null The item if found, otherwise null.
     */
    public function getById($entity, $id)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            return null;
        }

        $items = $data[$entity];

        foreach ($items as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }

        return null;
    }
    
    /**
     * Creates a new item in the specified entity.
     *
     * @param string $entity The name of the entity.
     * @param array $item The item data to create.
     * @param bool $descending If true, the new item is added to the beginning of the array (default: true).
     * @return array The newly created item, including its generated ID.
     */
    public function create($entity, $item,$descending = true)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            $data[$entity] = [];
        }

        $item['id'] = uniqid(); // Generate a unique ID (you may want to use a more robust method)

        if($descending != true) {
            $data[$entity][] = $item;
        }else{
            array_unshift($data[$entity],$item);
        }
        
        $this->saveData($data);

        return $item;
    }
    
    /**
     * Updates an existing item in the specified entity by its ID.
     *
     * @param string $entity The name of the entity.
     * @param string $id The ID of the item to update.
     * @param array $updatedItem An associative array of fields and their new values.
     * @return array|null The updated item if found, otherwise null.
     */
    public function update($entity, $id, $updatedItem)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            return null;
        }

        $items = &$data[$entity];

        foreach ($items as &$item) {
            if ($item['id'] == $id) {
                $item = array_merge($item, $updatedItem);
                $this->saveData($data);
                return $item;
            }
        }

        return null;
    }
    
    /**
     * Deletes an item from the specified entity by its ID.
     *
     * @param string $entity The name of the entity.
     * @param string $id The ID of the item to delete.
     * @return array|null The deleted item if found, otherwise null.
     */
    public function delete($entity, $id)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            return null;
        }

        $items = &$data[$entity];

        foreach ($items as $key => $item) {
            if ($item['id'] == $id) {
                unset($items[$key]);
                $this->saveData($data);
                return $item;
            }
        }

        return null;
    }
    
    /**
     * Searches for items within an entity based on a value.
     * Can search specific fields or all fields.
     *
     * @param string $entity The name of the entity.
     * @param mixed $value The value to search for.
     * @param array|bool $fields An array of fields to search, or false to search all fields.
     * @return array|null An array of matching items, or null if no matches are found.
     */
    public function search($entity,$value,$fields = false)
    {

        if($fields !=false){
            return $this->searchFields($entity, $fields, $value);
        }else{
            return $this->searchAllFields($entity, $value);
        }
    
    } 
    /**
     * Searches for items within a specified entity where a given field matches a value.
     *
     * @param string $entity The name of the entity.
     * @param array $fields An array of field names to search within.
     * @param mixed $value The value to match.
     * @return array|null An array of matching items, or null if no matches are found.
     */
   public function searchFields($entity, $fields, $value)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return [];
       }
   
       $items = $data[$entity];
       $results = [];
   
       foreach ($items as $item) {
           foreach ($fields as $field) {
               if (isset($item[$field]) && $item[$field] == $value) {
                   $results[] = $item;
                   break 2; // Breaks out of both loops once a match is found
               }
           }
       }
   
       return count($results) > 0? $results : null;
   }
    
    /**
     * Searches for items within a specified entity where any field matches a value.
     *
     * @param string $entity The name of the entity.
     * @param mixed $value The value to match.
     * @return array|null An array of matching items, or null if no matches are found.
     */
   public function searchAllFields($entity, $value)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return [];
       }
   
       $items = $data[$entity];
       $results = [];
   
       foreach ($items as $item) {
           foreach ($item as $field => $fieldValue) {
               if ($fieldValue == $value) {
                   $results[] = $item;
                   break 2; // Breaks out of both loops once a match is found
               }
           }
       }
   
       return count($results) > 0? $results : null;
   }
    
    /**
     * Searches for items within a specified entity where a numeric field's value falls within a given range.
     *
     * @param string $entity The name of the entity.
     * @param string $field The name of the numeric field to search within.
     * @param numeric $lowerBound The lower bound of the range (inclusive).
     * @param numeric $upperBound The upper bound of the range (inclusive).
     * @return array|null An array of matching items, or null if no matches are found.
     */
   private function searchBetween($entity, $field, $lowerBound, $upperBound)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return [];
       }
   
       $items = $data[$entity];
       $results = [];
   
       foreach ($items as $item) {
           if (isset($item[$field]) && $item[$field] >= $lowerBound && $item[$field] <= $upperBound) {
               $results[] = $item;
           }
       }
   
       return count($results) > 0? $results : null;
   }
    
    /**
     * Searches for items within a specified entity where any field's value contains a given pattern (case-sensitive).
     *
     * @param string $entity The name of the entity.
     * @param string $pattern The substring pattern to search for.
     * @return array An array of matching items.
     */
   private function searchWordsLike($entity, $pattern)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return [];
       }
   
       $items = $data[$entity];
       $results = [];
   
       foreach ($items as $item) {
           foreach ($item as $field => $fieldValue) {
               if (strpos($fieldValue, $pattern)!== false) {
                   $results[] = $item;
                   break; // Break out of the inner loop once a match is found
               }
           }
       }
   
       return $results;
   }
    
    /**
     * Sorts the items within a specified entity by a given field and order.
     *
     * @param string $entity The name of the entity.
     * @param string $field The field to sort by.
     * @param string $order The sort order ('asc' for ascending, 'desc' for descending).
     * @return array The sorted array of items.
     */
   public function sort($entity, $field, $order = 'asc')
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return []; // Return empty array if entity doesn't exist
       }
   
       usort($data[$entity], function($a, $b) use ($field, $order) {
           if ($order === 'asc') {
               return strcmp($a[$field], $b[$field]); // For strings
               // return $a[$field] <=> $b[$field]; // For PHP 7.0+
           } else { // Descending order
               return strcmp($b[$field], $a[$field]); // For strings
               // return $b[$field] <=> $a[$field]; // For PHP 7.0+
           }
       });
   
       return $data[$entity];
   }
    
    /**
     * Checks if any item in an entity has a field value within a specified range.
     *
     * @param string $entity The name of the entity.
     * @param string $field The field to check.
     * @param numeric $lowerBound The lower bound of the range.
     * @param numeric $upperBound The upper bound of the range.
     * @return array|null An array of matching items, or null if no matches are found.
     */
   public function isBetween($entity, $field, $lowerBound, $upperBound){
        return $this->searchBetween($entity, $field, $lowerBound, $upperBound);
   }
    
    /**
     * Checks if any item in an entity has a field value containing a specified pattern.
     *
     * @param string $entity The name of the entity.
     * @param string $pattern The pattern to search for.
     * @return bool True if a match is found, false otherwise.
     */
   public function isLike($entity, $pattern){
        return $this->searchWordsLike($entity, $pattern) !== false;
   }
    
    /**
     * Checks if any item in an entity has a field value containing a specified pattern (alias for isLike).
     *
     * @param string $entity The name of the entity.
     * @param string $pattern The pattern to search for.
     * @return bool True if a match is found, false otherwise.
     */
   public function orContain($entity, $pattern){
        return $this->searchWordsLike($entity, $pattern) !== false;
   }
    
    /**
     * Exports data from an entity to a CSV file. Can export all fields or selected fields.
     *
     * @param string $entity The name of the entity to export.
     * @param string $csvFilename The name of the CSV file to create.
     * @param array|bool $selectedFields An array of field names to export, or false to export all fields.
     * @return void
     */
    public function export($entity,$csvFilename,$selectedFields = false)
    {
        if($selectedFields == false){
            return $this->exportSelectedFieldsToCsv($entity, $selectedFields, $csvFilename);
        }else{
            return $this->exportToCsv($entity, $csvFilename);
        }
    }
    
    /**
     * Imports data from a CSV file into an entity. Can import all fields or selected fields.
     *
     * @param string $entity The name of the entity to import into.
     * @param string $csvFilename The path to the CSV file.
     * @param array|bool $selectedFields An array of field names to import, or false to import all fields.
     */
    public function import($entity,$csvFilename, $selectedFields = false){
        if($selectedFields == false){
            return $this->importFromCsv($entity, $csvFilename);
        }else{
            return $this->importSelectedFieldsFromCsv($entity, $selectedFields, $csvFilename);
        }
    }
    
    /**
     * Performs aggregation calculations (sum, average, max, min) on a specified numeric field within an entity.
     *
     * @param string $entity The name of the entity.
     * @param string $operator The aggregation operation ('sum', 'avg', 'average', 'max', 'min').
     * @param string $field The numeric field to perform the calculation on.
     * @return numeric The result of the calculation, or 0 if the entity or field is invalid.
     */
    public function calculate($entity,$operator,$field)
    {

        switch($operator){
            case'sum':
                return $this->sum($entity, $field);
            break;

            case'average':
            case'avg':
                return $this->average($entity, $field);
            break;

            case'max':
                return $this->max($entity, $field);
            break;

            case'min':
                return $this->min($entity, $field);
            break;

            default:
                return 0;

        }

    }
    
    /**
     * Counts the number of items in an entity where a specified field matches a given value.
     *
     * @param string $entity The name of the entity.
     * @param string $field The field to check.
     * @param mixed $value The value to match.
     * @return int The count of matching items.
     */
    public function countIf($entity, $field, $value)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return 0;
       }
   
       $count = 0;
   
       foreach ($data[$entity] as $item) {
           if (isset($item[$field]) && $item[$field] == $value) {
               $count++;
           }
       }
   
       return $count;
   }
    
    /**
     * Calculates the sum of a numeric field across all items in an entity.
     *
     * @param string $entity The name of the entity.
     * @param string $field The numeric field to sum.
     * @return numeric The sum of the field values, or 0 if no numeric values are found.
     */
   private function sum($entity, $field)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return 0;
       }
   
       $total = 0;
   
       foreach ($data[$entity] as $item) {
           if (isset($item[$field]) && is_numeric($item[$field])) {
               $total += $item[$field];
           }
       }
   
       return $total;
   }

    /**
     * Calculates the sum of field_a grouped by field_b values.
     *
     * @param string $entity The name of the entity
     * @param string $field_a The field to sum
     * @param string $field_b The field to group by
     * @return array Associative array of sums keyed by field_b values
     */
    private function sumIf($entity, $field_a, $field_b)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            return [];
        }

        $sums = [];

        foreach ($data[$entity] as $item) {
            if (isset($item[$field_a]) && isset($item[$field_b]) && is_numeric($item[$field_a])) {
                    $groupKey = $item[$field_b];
                    if (!isset($sums[$groupKey])) {
                        $sums[$groupKey] = 0;
                    }
                    $sums[$groupKey] += $item[$field_a];
            }
        }

        return $sums;
    }
    
    /**
     * Calculates the average of a numeric field across all items in an entity.
     *
     * @param string $entity The name of the entity.
     * @param string $field The numeric field to average.
     * @return numeric The average of the field values, or 0 if no numeric values are found or division by zero.
     */
    private function average($entity, $field)
    {
        $data = $this->loadData();
    
        if (!isset($data[$entity])) {
            return 0;
        }
    
        $totalSum = 0;
        $totalItems = count($data[$entity]);
    
        foreach ($data[$entity] as $item) {
            if (isset($item[$field]) && is_numeric($item[$field])) {
                $totalSum += $item[$field];
            }
        }
    
        if ($totalItems == 0) {
            return 0; // Avoid division by zero
        }
    
        return $totalSum / $totalItems;
    }
    
    /**
     * Finds the maximum value of a numeric field across all items in an entity.
     *
     * @param string $entity The name of the entity.
     * @param string $field The numeric field to find the maximum value from.
     * @return numeric|null The maximum value, or null if no numeric values are found.
     */
    private function max($entity, $field)
    {
         $data = $this->loadData();

         if (!isset($data[$entity])) {
              return null; // Entity does not exist
         }

         $maxValue = null;
         $foundNumeric = false;

         foreach ($data[$entity] as $item) {
              if (isset($item[$field]) && is_numeric($item[$field])) {
                    $currentValue = $item[$field];

                    if (!$foundNumeric) {
                         $maxValue = $currentValue;
                         $foundNumeric = true;
                    } elseif ($currentValue > $maxValue) {
                         $maxValue = $currentValue;
                    }
              }
         }

         return $maxValue; // Returns null if no numeric values found
    }
    
    /**
     * Finds the minimum value of a numeric field across all items in an entity.
     *
     * @param string $entity The name of the entity.
     * @param string $field The numeric field to find the minimum value from.
     * @return numeric|null The minimum value, or null if no numeric values are found.
     */
    private function min($entity, $field)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
             return null; // Entity does not exist
        }

        $minValue = null;
        $foundNumeric = false;

        foreach ($data[$entity] as $item) {
             if (isset($item[$field]) && is_numeric($item[$field])) {
                   $currentValue = $item[$field];

                   if (!$foundNumeric) {
                        $minValue = $currentValue;
                        $foundNumeric = true;
                   } elseif ($currentValue < $minValue) {
                        $minValue = $currentValue;
                   }
             }
        }

        return $minValue; // Returns null if no numeric values found
   }
    
    /**
     * Exports all data from a specified entity to a CSV file.
     *
     * @param string $entity The name of the entity to export.
     * @param string $csvFilename The name of the CSV file to create.
     * @throws Exception If the entity does not exist.
     */
    private function exportToCsv($entity, $csvFilename)
    {
        $data = $this->loadData();
    
        if (!isset($data[$entity])) {
            throw new Exception("Entity '$entity' does not exist.");
        }
    
        $csvContent = "";
    
        // Add headers to the CSV content
        $headers = array_keys((array)$data[$entity][0]); // Assuming all items have the same structure
        $csvContent.= implode(",", $headers). "\n"; // First row with column names
    
        // Add data rows to the CSV content
        foreach ($data[$entity] as $row) {
            $csvContent.= implode(",", array_map(function($cell) {
                return "\"$cell\""; // Wrap cell values in quotes to handle commas and newlines properly
            }, $row)). "\n";
        }
    
        // Write the CSV content to a file
        file_put_contents($csvFilename, $csvContent);
    
        echo "CSV file exported successfully.";
    }
    
    /**
     * Exports selected fields from a specified entity to a CSV file.
     *
     * @param string $entity The name of the entity to export.
     * @param array $selectedFields An array of field names to include in the CSV.
     * @param string $csvFilename The name of the CSV file to create.
     * @throws Exception If the entity does not exist.
     */
    private function exportSelectedFieldsToCsv($entity, $selectedFields, $csvFilename)
    {
        $data = $this->loadData();
    
        if (!isset($data[$entity])) {
            throw new Exception("Entity '$entity' does not exist.");
        }
    
        $csvContent = "";
    
        // Determine headers based on selected fields
        $headers = $selectedFields;
        $csvContent.= implode(",", $headers). "\n"; // First row with column names
    
        // Add data rows to the CSV content
        foreach ($data[$entity] as $row) {
            $rowData = [];
            foreach ($selectedFields as $field) {
                if (isset($row[$field])) {
                    $rowData[] = "\"". $row[$field]. "\""; // Wrap cell values in quotes
                } else {
                    $rowData[] = ""; // Empty cell if field is not set
                }
            }
            $csvContent.= implode(",", $rowData). "\n";
        }
    
        // Write the CSV content to a file
        file_put_contents($csvFilename, $csvContent);
    
        echo "CSV file exported successfully.";
    }
    
    /**
     * Imports data from a CSV file into a specified entity.
     * Assumes the first row of the CSV contains headers that match JSON keys.
     *
     * @param string $entity The name of the entity to import into.
     * @param string $csvFilename The path to the CSV file.
     * @throws Exception If the CSV file cannot be read or opened.
     */
    private function importFromCsv($entity, $csvFilename)
    {
        $data = $this->loadData();
    
        if (!isset($data[$entity])) {
            $data[$entity] = []; // Initialize entity if it doesn't exist
        }
    
        // Check if the CSV file exists and is readable
        if (!is_readable($csvFilename)) {
            throw new Exception("Cannot read the CSV file: $csvFilename");
        }
    
        // Open the CSV file
        $handle = fopen($csvFilename, 'r');
        if (!$handle) {
            throw new Exception("Failed to open the CSV file: $csvFilename");
        }
    
        // Read the first line to get the headers
        $headers = fgetcsv($handle);
    
        // Loop through the rest of the lines and convert them to associative arrays
        while (($row = fgetcsv($handle))!== FALSE) {
            $item = array_combine($headers, $row); // Combine headers with row values
            $data[$entity][] = $item; // Add the item to the entity
        }
    
        fclose($handle);
    
        // Save the updated data back to the JSON file
        $this->saveData($data);
    
        echo "CSV file imported successfully.";
    }
    
    /**
     * Imports data from a CSV file into a specified entity, mapping only selected fields.
     *
     * @param string $entity The name of the entity to import into.
     * @param array $selectedFields An array of field names to import from the CSV.
     * @param string $csvFilename The path to the CSV file.
     * @throws Exception If the CSV file cannot be read or opened.
     */
    private function importSelectedFieldsFromCsv($entity, $selectedFields, $csvFilename)
    {
        $data = $this->loadData();
    
        if (!isset($data[$entity])) {
            $data[$entity] = []; // Initialize entity if it doesn't exist
        }
    
        // Check if the CSV file exists and is readable
        if (!is_readable($csvFilename)) {
            throw new Exception("Cannot read the CSV file: $csvFilename");
        }
    
        // Open the CSV file
        $handle = fopen($csvFilename, 'r');
        if (!$handle) {
            throw new Exception("Failed to open the CSV file: $csvFilename");
        }
    
        // Read the first line to get the headers
        $headers = fgetcsv($handle);
    
        // Prepare a map of selected fields to their indices for quick access
        $fieldIndices = array_flip(array_intersect_key($headers, $selectedFields));
    
        // Loop through the rest of the lines and convert them to associative arrays
        while (($row = fgetcsv($handle)!== FALSE)) {
            $item = [];
            foreach ($selectedFields as $field) {
                $index = $fieldIndices[$field];
                if ($index!== null) { // Check if the field is in the selected fields list
                    $item[$field] = $row[$index]; // Assign value to the item array
                }
            }
            $data[$entity][] = $item; // Add the item to the entity
        }
    
        fclose($handle);
    
        // Save the updated data back to the JSON file
        $this->saveData($data);
    
        echo "CSV file imported successfully.";
    }
    
    /**
     * Exports data from a specified entity to an SQLite database table.
     *
     * @param string $entity The name of the entity to export.
     * @param string $dbPath The path to the SQLite database file.
     * @param string $tableName The name of the table to create/insert into in SQLite.
     * @throws Exception If the entity does not exist or SQLite export fails.
     */
    public function exportToSqlite($entity, $dbPath, $tableName)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            throw new Exception("Entity '$entity' does not exist.");
        }

        try {
            $pdo = new PDO("sqlite:" . $dbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $items = $data[$entity];
            if (empty($items)) {
                echo "No data to export for entity '$entity'.";
                return;
            }

            // Get column names from the first item
            $columns = array_keys($items[0]);
            $columnDefinitions = [];
            foreach ($columns as $col) {
                $columnDefinitions[] = "`" . $col . "` TEXT"; // Assuming all are text for simplicity
            }
            $columnsSql = implode(", ", $columnDefinitions);
            $placeholders = implode(", ", array_fill(0, count($columns), "?"));
            $columnNames = implode("`, `", $columns);

            // Create table
            $pdo->exec("DROP TABLE IF EXISTS `" . $tableName . "`");
            $pdo->exec("CREATE TABLE `" . $tableName . "` (" . $columnsSql . ")");

            // Prepare insert statement
            $insertSql = "INSERT INTO `" . $tableName . "` (`" . $columnNames . "`) VALUES (" . $placeholders . ")";
            $stmt = $pdo->prepare($insertSql);

            // Insert data
            foreach ($items as $item) {
                $values = array_values($item);
                $stmt->execute($values);
            }

            echo "Data for entity '$entity' exported to SQLite table '$tableName' successfully.";
        } catch (PDOException $e) {
            error_log("SQLite export failed: " . $e->getMessage());
            throw new Exception("Failed to export to SQLite: " . $e->getMessage());
        }
    }

    /**
     * Filters data based on a specified field, operator, and value, mimicking SQL's WHERE clause.
     *
     * @param string $entity The name of the entity to query.
     * @param string $field The field to apply the condition on.
     * @param string $operator The comparison operator (e.g., '=', '!=', '>', '<', '>=', '<=', 'is_null', 'is_not_null', 'is_empty', 'is_not_empty').
     * @param mixed $value The value to compare against (optional, not used for 'is_null', 'is_not_null', 'is_empty', 'is_not_empty').
     * @return array An array of matching items.
     */
    public function where(string $entity, string $field, string $operator, mixed $value = null): array
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            return [];
        }

        $items = $data[$entity];
        $results = [];

        foreach ($items as $item) {
            $fieldExists = isset($item[$field]);
            $fieldValue = $fieldExists ? $item[$field] : null;

            switch ($operator) {
                case '=':
                case '==':
                    if ($fieldExists && $fieldValue == $value) {
                        $results[] = $item;
                    }
                    break;
                case '!=':
                case '<>':
                    if ($fieldExists && $fieldValue != $value) {
                        $results[] = $item;
                    }
                    break;
                case '>':
                    if ($fieldExists && is_numeric($fieldValue) && is_numeric($value) && $fieldValue > $value) {
                        $results[] = $item;
                    }
                    break;
                case '<':
                    if ($fieldExists && is_numeric($fieldValue) && is_numeric($value) && $fieldValue < $value) {
                        $results[] = $item;
                    }
                    break;
                case '>=':
                    if ($fieldExists && is_numeric($fieldValue) && is_numeric($value) && $fieldValue >= $value) {
                        $results[] = $item;
                    }
                    break;
                case '<=':
                    if ($fieldExists && is_numeric($fieldValue) && is_numeric($value) && $fieldValue <= $value) {
                        $results[] = $item;
                    }
                    break;
                case 'is_null':
                    if (!$fieldExists || $fieldValue === null) {
                        $results[] = $item;
                    }
                    break;
                case 'is_not_null':
                    if ($fieldExists && $fieldValue !== null) {
                        $results[] = $item;
                    }
                    break;
                case 'is_empty':
                    if ($fieldExists && empty($fieldValue) && $fieldValue !== false) {
                        $results[] = $item;
                    }
                    break;
                case 'is_not_empty':
                    if ($fieldExists && !empty($fieldValue)) {
                        $results[] = $item;
                    }
                    break;
                    
                default:
                    $results = false;
            }
        }

        return $results;
    }

    /**
     * Establishes a one-to-many relationship between two entities.
     *
     * Acts as a dispatcher that chooses the correct internal helper based on
     * whether the entities live in the same JSON file or in two separate files.
     *
     * @param string      $primaryFile Path to the JSON file that contains the primary entity
     *                                 (or the only file when $mode = 'singleJson').
     * @param string|null $relatedFile Path to the JSON file that contains the related entity.
     *                                 Ignored when $mode = 'singleJson'.
     * @param string      $field       Relationship specification in the form
     *                                 "primaryEntity,relatedEntity,commonColumn".
     * @param string      $mode        'singleJson' – both entities are in $primaryFile.
     *                                 'multiJson'  – entities are in separate files.
     *
     * @return array Associative array keyed by the primary record’s id; each value
     *               is an array of related records.
     */
    public function relationship($primaryFile, $relatedFile = null, $field, $mode = 'singleJson')
    {
        if ($mode == 'singleJson') {
            return $this->one_to_many_singleJsonFile($primaryFile, $field);
        } elseif ($mode == 'multiJson') {
            return $this->one_to_manyMuiltJsonFile($primaryFile, $relatedFile, $field);
        }
    }

    /**
     * Establishes a one-to-many relationship between two JSON files.
     *
     * The third parameter is a one-element array whose single string contains the
     * mapping in the form:  ['primaryEntity,relatedEntity,commonColumn']
     *
     * @param string $primaryFile
     * @param string $relatedFile
     * @param string $field   A single string taken from the first (and only) element
     *                        of the passed array, e.g. 'users,comments,user_id'
     * @return array
     */
    private function one_to_manyMuiltJsonFile(string $primaryFile, string $relatedFile, /*array*/ string $field): array
    {
        // --- 1.  Parse the mapping string ----------------------------------------
        [$primaryEntity, $relatedEntity, $column] = array_map('trim', explode(',', $field));

        // --- 2.  Load both JSON files --------------------------------------------
        $primaryData = $this->loadData($primaryFile);
        $relatedData = $this->loadData($relatedFile);

        // --- 3.  Build lookup map for the “many” side -----------------------------
        $relatedMap = [];
        foreach (($relatedData[$relatedEntity] ?? []) as $record) {
            if (!is_array($record) || !isset($record[$column])) {
                continue;
            }
            $relatedMap[$record[$column]][] = $record;
        }

        // --- 4.  Attach related records to the “one” side -------------------------
        $result = [];
        foreach (($primaryData[$primaryEntity] ?? []) as $record) {
            if (!is_array($record) || !isset($record['id'])) {
                continue;
            }
            $key = $record[$column] ?? null;
            $result[$record['id']] = $relatedMap[$key] ?? [];
        }

        return $result;
    }

    /**
     * Establishes a one-to-many relationship between two JSON entities that live
     * in the **same file**.
     *
     * @param string $file   Path to the single JSON file that contains both entities.
     * @param string $spec   Format: "primaryEntity,relatedEntity,commonColumn"
     *                       Example: "users,comments,user_id"
     *
     * @return array  Associative array keyed by the primary record’s id,
     *                each value is an array of related records.
     *
     * @throws Exception
     */
    private function one_to_many_singleJsonFile(string $file, string $spec): array
    {
        // 1. Parse the specification
        [$primaryEntity, $relatedEntity, $column] = array_map('trim', explode(',', $spec));

        // 2. Load the single JSON file
        $json = file_get_contents($file);
        if ($json === false) {
            throw new Exception("Cannot read file: $file");
        }
        $data = json_decode($json, true);
        if (!is_array($data)) {
            throw new Exception("Invalid JSON in file: $file");
        }

        // 3. Build lookup map for the “many” side
        $relatedMap = [];
        foreach (($data[$relatedEntity] ?? []) as $record) {
            if (!is_array($record) || !isset($record[$column])) {
                continue;
            }
            $relatedMap[$record[$column]][] = $record;
        }

        // 4. Attach related records to the “one” side
        $result = [];
        foreach (($data[$primaryEntity] ?? []) as $record) {
            if (!is_array($record) || !isset($record['id'])) {
                continue;
            }
            $key = $record[$column] ?? null;
            $result[$record['id']] = $relatedMap[$key] ?? [];
        }

        return $result;
    }
}