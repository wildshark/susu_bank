<?php

/**
 * CsvFile class provides functionality to read, write, and manipulate CSV data.
 * It loads CSV data into memory, allows adding, updating, deleting, searching,
 * filtering, and sorting records, and persists changes back to the file.
 */
class CsvFile
{
    private string $filePath;
    private array $headers = [];
    private array $records = [];

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->load();
    }

    private function load(): void
    {
        if (!file_exists($this->filePath) || !is_readable($this->filePath)) {
            $this->headers = [];
            $this->records = [];
            return;
        }

        $handle = fopen($this->filePath, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Could not open CSV file for reading: " . $this->filePath);
        }

        $this->headers = fgetcsv($handle);
        if ($this->headers === false) {
            $this->headers = [];
            $this->records = [];
            fclose($handle);
            return;
        }

        $this->records = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($this->headers)) {
                $this->records[] = array_combine($this->headers, $row);
            }
        }
        fclose($handle);
    }

    private function save(): void
    {
        $handle = fopen($this->filePath, 'w');
        if ($handle === false) {
            throw new \RuntimeException("Could not open CSV file for writing: " . $this->filePath);
        }

        if (!empty($this->headers)) {
            fputcsv($handle, $this->headers);
        }

        foreach ($this->records as $record) {
            // Ensure the record values are in the correct order based on headers
            $orderedRow = [];
            foreach ($this->headers as $header) {
                $orderedRow[] = $record[$header] ?? ''; // Use empty string if header not found in record
            }
            fputcsv($handle, $orderedRow);
        }
        fclose($handle);
    }

    public function getRecords(): array
    {
        return $this->records;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function addRecord(array $newRecord): bool
    {
        if (empty($this->headers)) {
            $this->headers = array_keys($newRecord);
        }

        // Ensure the new record has all headers, fill missing with empty string
        $recordToAdd = [];
        foreach ($this->headers as $header) {
            $recordToAdd[$header] = $newRecord[$header] ?? '';
        }

        $this->records[] = $recordToAdd;
        $this->save();
        return true;
    }

    public function updateRecord(int $index, array $updatedData): bool
    {
        if (!isset($this->records[$index])) {
            return false;
        }

        foreach ($updatedData as $key => $value) {
            if (in_array($key, $this->headers)) {
                $this->records[$index][$key] = $value;
            }
        }
        $this->save();
        return true;
    }

    public function deleteRecord(int $index): bool
    {
        if (!isset($this->records[$index])) {
            return false;
        }
        array_splice($this->records, $index, 1);
        $this->save();
        return true;
    }

    public function search(string $column, string $searchValue, bool $caseSensitive = false): array
    {
        if (!in_array($column, $this->headers)) {
            return [];
        }

        $results = [];
        foreach ($this->records as $record) {
            $recordValue = $record[$column] ?? '';
            if ($caseSensitive) {
                if ($recordValue === $searchValue) {
                    $results[] = $record;
                }
            } else {
                if (strtolower($recordValue) === strtolower($searchValue)) {
                    $results[] = $record;
                }
            }
        }
        return $results;
    }

    public function filter(callable $callback): array
    {
        return array_filter($this->records, $callback);
    }

    public function sort(string $column, int $order = SORT_ASC): void
    {
        if (!in_array($column, $this->headers)) {
            throw new \InvalidArgumentException("Column '{$column}' does not exist for sorting.");
        }

        usort($this->records, function ($a, $b) use ($column, $order) {
            $valA = $a[$column] ?? '';
            $valB = $b[$column] ?? '';

            if (is_numeric($valA) && is_numeric($valB)) {
                $cmp = $valA <=> $valB;
            } else {
                $cmp = strcasecmp($valA, $valB);
            }

            return ($order === SORT_ASC) ? $cmp : -$cmp;
        });
        // Sorting only affects the in-memory records, not saved automatically
        // Call save() explicitly if you want to persist the sort order
    }
}

?>