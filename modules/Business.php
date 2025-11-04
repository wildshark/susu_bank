<?php

/**
 * Class Business
 * * Handles all CRUD operations for the 'business' table.
 * Assumes a Database::connect() method returns a valid PDO instance.
 */
class Business {

    /**
     * @var PDO Database connection object
     */
    private $_db;

    /**
     * Constructor establishes the database connection.
     */
    public function __construct() {
        // This line is from your provided code
        $this->_db = Database::connect(); 
    }

    /**
     * CREATE: Adds a new business record to the database.
     *
     * @param array $data Associative array of business data.
     * @return bool True on success, false on failure.
     */
    public function createBusiness($data) {
        // Note: Passwords should be hashed *before* being passed in $data
        // e.g., $data['password'] = password_hash($plainPassword, PASSWORD_DEFAULT);

        $sql = "INSERT INTO business (
                    agentId, cdate, lmodify, businessId, businessName, 
                    firstName, midName, lastName, mobile, email, 
                    postalAddress, digitalAddress, region, district, 
                    username, password, status
                ) VALUES (
                    ?, NOW(), NOW(), ?, ?, 
                    ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, 
                    ?, ?, ?
                )";
        
        $stmt = $this->_db->prepare($sql);
        
        return $stmt->execute([
            $data['agentId'],
            $data['businessId'],
            $data['businessName'],
            $data['firstName'],
            $data['midName'],
            $data['lastName'],
            $data['mobile'],
            $data['email'],
            $data['postalAddress'],
            $data['digitalAddress'],
            $data['region'],
            $data['district'], // Matched 'district' typo from your schema
            $data['username'],
            $data['password'], // IMPORTANT: Store hashed passwords only!
            $data['status']
        ]);
    }

    /**
     * Authenticates a business user.
     *
     * @param string $username The username to check
     * @param string $password The password to verify
     * @return array|false User data if authenticated, false otherwise
     */
    public function login($usernameOrEmail, $password) {
        $sql = "SELECT * FROM business WHERE username = ? OR email = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    /**
     * Initiates password reset process for a business user by email.
     *
     * @param string $email The email address to search for
     * @return array|false User data if found, false otherwise
     */
    public function initiatePasswordReset($email) {
        $sql = "SELECT agentId, username, email FROM business WHERE email = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $resetToken = bin2hex(random_bytes(32));
            $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $updateSql = "UPDATE business SET reset_token = ?, reset_token_expiry = ? WHERE agentId = ?";
            $updateStmt = $this->_db->prepare($updateSql);
            $updateStmt->execute([$resetToken, $tokenExpiry, $user['agentId']]);
            
            $user['reset_token'] = $resetToken;
            return $user;
        }
        return false;
    }

    /**
     * READ: Fetches a single business record by its primary key (agentId).
     *
     * @param int $agentId The primary key of the business.
     * @return array|false An associative array of the business data or false if not found.
     */
    public function getBusinessById($agentId) {
        $sql = "SELECT * FROM business WHERE agentId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([$agentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * READ: Fetches all business records.
     *
     * @return array|false An array of associative arrays or false on failure.
     */
    public function getAllBusinesses() {
        $sql = "SELECT * FROM business ORDER BY businessName ASC";
        $stmt = $this->_db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * UPDATE: Modifies an existing business record identified by agentId.
     *
     * @param int $agentId The primary key of the record to update.
     * @param array $data Associative array of new data.
     * @return bool True on success, false on failure.
     */
    public function updateBusiness($agentId, $data) {
        // Again, ensure $data['password'] is hashed if it's being updated
        $sql = "UPDATE business SET 
                    lmodify = NOW(),
                    businessId = ?,
                    businessName = ?,
                    firstName = ?,
                    midName = ?,
                    lastName = ?,
                    mobile = ?,
                    email = ?,
                    postalAddress = ?,
                    digitalAddress = ?,
                    region = ?,
                    district = ?,
                    username = ?,
                    password = ?,
                    status = ?
                WHERE agentId = ?";
        
        $stmt = $this->_db->prepare($sql);
        
        return $stmt->execute([
            $data['businessId'],
            $data['businessName'],
            $data['firstName'],
            $data['midName'],
            $data['lastName'],
            $data['mobile'],
            $data['email'],
            $data['postalAddress'],
            $data['digitalAddress'],
            $data['region'],
            $data['district'],
            $data['username'],
            $data['password'],
            $data['status'],
            $agentId // This is for the WHERE clause
        ]);
    }

    /**
     * DELETE: Removes a business record from the database.
     *
     * @param int $agentId The primary key of the record to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteBusiness($agentId) {
        $sql = "DELETE FROM business WHERE agentId = ?";
        $stmt = $this->_db->prepare($sql);
        return $stmt->execute([$agentId]);
    }
}
?>
