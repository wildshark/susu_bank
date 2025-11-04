<?php

use PDO;
use PDOException;

/**
 * Class Ledger
 * * Handles all CRUD operations for the 'ledger' table.
 * * Assumes a Database::connect() method returns a valid PDO instance.
 */
class Ledger {

    /**
     * @var PDO Database connection object
     */
    private $_db;

    /**
     * Constructor establishes the database connection.
     */
    public function __construct() {
        $this->_db = Database::connect();
    }

    /**
     * CREATE: Adds a new ledger entry (a debit or credit).
     * 'ledgerId' and 'updateDate' are set automatically by the database.
     *
     * @param array $data Associative array containing 'agentId', 'clientId', 'dr', 'cr'.
     * @return string|false The new 'ledgerId' on success, false on failure.
     */
    public function createLedgerEntry($data) {
        try {
            $sql = "INSERT INTO ledger (agentId, clientId, dr, cr) 
                    VALUES (?, ?, ?, ?)";
            
            $stmt = $this->_db->prepare($sql);
            
            $success = $stmt->execute([
                $data['agentId'],
                $data['clientId'],
                $data['dr'], // Debit amount
                $data['cr']  // Credit amount
            ]);

            if ($success) {
                return $this->_db->lastInsertId();
            } else {
                return false;
            }

        } catch (PDOException $e) {
            error_log("Error creating ledger entry: " . $e->getMessage());
            return false;
        }
    }

    /**
     * READ: Fetches all ledger entries for a specific agent,
     * joining with clients table to get client names and account numbers.
     *
     * @param int $agentId The agent's ID.
     * @return array An array of associative arrays. Empty array on failure.
     */
    public function getLedgerByAgentId($agentId) {
        try {
            $sql = "SELECT * FROM get_ledger WHERE agentId = ? ";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$agentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching ledger by agent: " . $e->getMessage());
            return false;
        }
    }


    /**
     * READ: Fetches a single ledger entry by its primary key.
     *
     * @param int $ledgerId The primary key of the ledger entry.
     * @return array|false An associative array or false if not found.
     */
    public function getLedgerEntry($ledgerId) {
        try {
            $sql = "SELECT * FROM ledger WHERE ledgerId = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$ledgerId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error fetching ledger entry: " . $e->getMessage());
            return false;
        }
    }

    /**
     * READ: Fetches all ledger entries for a specific client.
     *
     * @param int $clientId The client's ID.
     * @return array An array of associative arrays. Empty array on failure.
     */
    public function getLedgerByClientId($clientId) {
        try {
            // Ordered by date, newest first
            $sql = "SELECT * FROM get_ledger WHERE clientId = ? ORDER BY updateDate DESC";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$clientId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error fetching client ledger: " . $e->getMessage());
            return [];
        }
    }

    /**
     * UPDATE: Updates the debit amount on ledger entries for a specific client.
     * Note: This will update ALL entries for the given client.
     *
     * @param int $clientId The client's ID.
     * @param float $amt The new debit amount.
     * @return bool True on success, false on failure.
     */
    public function updateClientLedgerDr($clientId, $amt) {
        try {
            $sql = "UPDATE ledger SET dr = ? WHERE clientId = ?";
            $stmt = $this->_db->prepare($sql);
            return $stmt->execute([$amt, $clientId]);
        } catch (PDOException $e) {
            error_log("Error updating client debit: " . $e->getMessage());
            return false;
        }
    }

    /**
     * UPDATE: Updates the credit amount on ledger entries for a specific client.
     * Note: This will update ALL entries for the given client.
     *
     * @param int $clientId The client's ID.
     * @param float $amt The new credit amount.
     * @return bool True on success, false on failure.
     */
    public function updateClientLedgerCr($clientId, $amt) {
        try {
            $sql = "UPDATE ledger SET cr = ? WHERE clientId = ?";
            $stmt = $this->_db->prepare($sql);
            return $stmt->execute([$amt, $clientId]);
        } catch (PDOException $e) {
            error_log("Error updating client credit: " . $e->getMessage());
            return false;
        }
    }

    /**
     * READ: Calculates the current balance for a specific client.
     * Balance = Total Credits (cr) - Total Debits (dr)
     *
     * @param int $clientId The client's ID.
     * @return float|false The calculated balance or false on error.
     */
    public function getClientBalance($clientId) {
        try {
            $sql = "SELECT (SUM(cr) - SUM(dr)) AS balance 
                    FROM ledger 
                    WHERE clientId = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$clientId]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // If $result['balance'] is NULL (no transactions), return 0.00
            return $result ? (float)$result['balance'] : 0.00;

        } catch (PDOException $e) {
            error_log("Error calculating client balance: " . $e->getMessage());
            return false;
        }
    }


    /**
     * UPDATE: Modifies an existing ledger entry.
     * 'updateDate' will be updated automatically by the database.
     *
     * @param int $ledgerId The primary key of the record to update.
     * @param array $data Associative array of new data.
     * @return bool True on success, false on failure.
     */
    public function updateLedgerEntry($ledgerId, $data) {
        try {
            $sql = "UPDATE ledger SET 
                        agentId = ?,
                        clientId = ?,
                        dr = ?,
                        cr = ?
                    WHERE ledgerId = ?";
            
            $stmt = $this->_db->prepare($sql);
            
            return $stmt->execute([
                $data['agentId'],
                $data['clientId'],
                $data['dr'],
                $data['cr'],
                $ledgerId // This is for the WHERE clause
            ]);

        } catch (PDOException $e) {
            error_log("Error updating ledger entry: " . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETE: Removes a ledger entry.
     * WARNING: This is extremely bad accounting practice.
     * Do NOT delete ledger entries. Create a reversing entry instead.
     *
     * @param int $ledgerId The primary key of the record to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteLedgerEntry($ledgerId) {
        try {
            $sql = "DELETE FROM ledger WHERE ledgerId = ?";
            $stmt = $this->_db->prepare($sql);
            return $stmt->execute([$ledgerId]);

        } catch (PDOException $e) {
            error_log("Error deleting ledger entry: " . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETE: Removes all ledger entries for a specific client.
     * WARNING: This is extremely bad accounting practice.
     * Do NOT delete ledger entries. Create reversing entries instead.
     *
     * @param int $clientId The client's ID.
     * @return bool True on success, false on failure.
     */
    public function deleteLedgerByClientId($clientId) {
        try {
            $sql = "DELETE FROM ledger WHERE clientId = ?";
            $stmt = $this->_db->prepare($sql);
            return $stmt->execute([$clientId]);

        } catch (PDOException $e) {
            error_log("Error deleting ledger entries by client ID: " . $e->getMessage());
            return false;
        }
    }
}
?>