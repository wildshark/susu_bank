<?php

/**
 * Class Transactions
 * * Handles all CRUD operations for the 'transactions' table.
 * Assumes a Database::connect() method returns a valid PDO instance.
 */
class Transactions {

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
     * CREATE: Adds a new transaction to the database.
     * Note: 'tranId' is omitted as it's AUTO_INCREMENT.
     *
     * @param array $data Associative array of transaction data.
     * @return string|false The new 'tranId' on success, false on failure.
     */
    public function createTransaction($data) {
        try {
            // 'cDate' is set to NOW(). 'tranId' is auto-generated.
            $sql = "INSERT INTO transactions (
                        agentId, clientId, cDate, tranDate, details, ref, 
                        tranType, tranMethod, contribution, payout, amount, balance
                    ) VALUES (
                        ?, ?, NOW(), ?, ?, ?, 
                        ?, ?, ?, ?, ?, ?
                    )";
            
            $stmt = $this->_db->prepare($sql);
            
            $success = $stmt->execute([
                $data['agentId'],
                $data['clientId'],
                $data['tranDate'], // e.g., '2025-11-01'
                $data['details'],
                $data['ref'],
                $data['tranType'],
                $data['tranMethod'],
                $data['contribution'],
                $data['payout'],
                $data['amount'],
                $data['balance'] // IMPORTANT: See note on balance calculation
            ]);

            if ($success) {
                return $this->_db->lastInsertId();
            } else {
                return false;
            }

        } catch (PDOException $e) {
            error_log("Error creating transaction: " . $e->getMessage());
            return false;
        }
    }

    /**
     * READ: Fetches the current transaction for a specific agent using today's cDate.
     *
     * @param int $agentId The agent's ID.
     * @return array|false An associative array of the transaction data or false if not found.
     */
    public function getCurrentTransactionByAgentId($agentId) {
        try {
            $sql = "SELECT * FROM get_transaction WHERE agentId = ? AND DATE(cDate) = CURDATE() ORDER BY cDate DESC LIMIT 1";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$agentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching current transaction by agent: " . $e->getMessage());
            return false;
        }
    }

    /**
     * SUM: Calculates the total amount for all transactions by a specific agent.
     *
     * @param int $agentId The agent's ID.
     * @return float|false The sum of amounts, or false on failure.
     */
    public function getTotalAmountByAgentId($agentId) {
        try {
            $sql = "SELECT SUM(amount) AS total_amount FROM transactions WHERE agentId = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$agentId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (float)$result['total_amount'] : 0.0;
        } catch (PDOException $e) {
            error_log("Error summing amounts by agent: " . $e->getMessage());
            return false;
        }
    }

    /**
     * SUM: Calculates the total payout for all transactions by a specific agent.
     *
     * @param int $agentId The agent's ID.
     * @return float|false The sum of payouts, or false on failure.
     */
    public function getTotalPayoutByAgentId($agentId) {
        try {
            $sql = "SELECT SUM(payout) AS total_payout FROM transactions WHERE agentId = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$agentId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (float)$result['total_payout'] : 0.0;
        } catch (PDOException $e) {
            error_log("Error summing payouts by agent: " . $e->getMessage());
            return false;
        }
    }

    /**
     * SUM: Calculates the total amount for all transactions by a specific client.
     *
     * @param int $clientId The client's ID.
     * @return float|false The sum of amounts, or false on failure.
     */
    public function getBalanceByClient($clientId,$amt) {
        try {
            $sql = "SELECT SUM(amount) AS total_amount FROM transactions WHERE clientId = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$clientId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);            
            $bal = $result ? (float)$result['total_amount'] : 0;
            return $bal + $amt;
        } catch (PDOException $e) {
            error_log("Error summing amounts by client: " . $e->getMessage());
            return false;
        }
    }

    /**
     * READ: Fetches a single transaction by its primary key (tranId).
     *
     * @param int $tranId The primary key of the transaction.
     * @return array|false An associative array of the transaction data or false if not found.
     */
    public function getTransactionById($tranId) {
        try {
            $sql = "SELECT * FROM transactions WHERE tranId = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$tranId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error fetching transaction: " . $e->getMessage());
            return false;
        }
    }

    /**
     * READ: Fetches all transactions, ordered by date.
     *
     * @return array An array of associative arrays. Empty array on failure.
     */
    public function getAllTransactions() {
        try {
            // Order by transaction date (descending) is most common
            $sql = "SELECT * FROM transactions ORDER BY tranDate DESC, cDate DESC";
            $stmt = $this->_db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error fetching all transactions: " . $e->getMessage());
            return []; // Return an empty array on failure
        }
    }

    /**
     * READ: Fetches all transactions for a specific client.
     *
     * @param int $clientId The client's ID.
     * @return array An array of associative arrays. Empty array on failure.
     */
    public function getTransactionsByClientId($clientId) {
        try {
            $sql = "SELECT * FROM transactions WHERE clientId = ? ORDER BY tranId DESC, cDate DESC";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$clientId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error fetching client transactions: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * READ: Fetches all transactions processed by a specific agent.
     *
     * @param int $agentId The agent's ID.
     * @return array An array of associative arrays. Empty array on failure.
     */
    public function getTransactionsByAgentId($agentId) {
        try {
            $sql = "SELECT * FROM get_transaction WHERE agentId = ? ORDER BY tranDate DESC, cDate DESC";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$agentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error fetching agent transactions: " . $e->getMessage());
            return [];
        }
    }

    /**
     * UPDATE: Modifies an existing transaction.
     * Note: This is often complex. You may want to restrict what can be updated.
     * This function allows updating most fields.
     *
     * @param int $tranId The primary key of the record to update.
     * @param array $data Associative array of new data.
     * @return bool True on success, false on failure.
     */
    public function updateTransaction($tranId, $data) {
        try {
            // Note: cDate is generally not updated.
            $sql = "UPDATE transactions SET 
                        agentId = ?,
                        clientId = ?,
                        tranDate = ?,
                        details = ?,
                        ref = ?,
                        tranType = ?,
                        tranMethod = ?,
                        contribution = ?,
                        payout = ?,
                        amount = ?,
                        balance = ?
                    WHERE tranId = ?";
            
            $stmt = $this->_db->prepare($sql);
            
            return $stmt->execute([
                $data['agentId'],
                $data['clientId'],
                $data['tranDate'],
                $data['details'],
                $data['ref'],
                $data['tranType'],
                $data['tranMethod'],
                $data['contribution'],
                $data['payout'],
                $data['amount'],
                $data['balance'],
                $tranId // This is for the WHERE clause
            ]);

        } catch (PDOException $e) {
            error_log("Error updating transaction: " . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETE: Removes a transaction from the database.
     * WARNING: Deleting financial records is generally bad practice.
     * Consider using a 'status' column (e.g., 'Cancelled', 'Reversed') instead.
     *
     * @param int $tranId The primary key of the record to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteTransaction($tranId) {
        try {
            $sql = "DELETE FROM transactions WHERE tranId = ?";
            $stmt = $this->_db->prepare($sql);
            return $stmt->execute([$tranId]);

        } catch (PDOException $e) {
            error_log("Error deleting transaction: " . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETE: Removes all transactions for a specific client.
     * WARNING: Deleting financial records is generally bad practice.
     * Consider using a 'status' column or archiving records instead.
     *
     * @param int $clientId The ID of the client whose records are to be deleted.
     * @return bool True on success, false on failure.
     */
    public function deleteTransactionsByClientId($clientId) {
        try {
            $sql = "DELETE FROM transactions WHERE clientId = ?";
            $stmt = $this->_db->prepare($sql);
            return $stmt->execute([$clientId]);

        } catch (PDOException $e) {
            error_log("Error deleting transactions by client ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * READ: Fetches monthly transaction summaries (contributions and payouts) for a specific agent.
     *
     * @param int $agentId The agent's ID.
     * @return array An array of associative arrays, each containing 'year', 'month', 'total_contribution', 'total_payout'.
     *               Empty array on failure.
     */
    public function getMonthlyTransactionSummaryByAgentId($agentId) {
        try {
            $sql = "SELECT 
                        YEAR(tranDate) AS year,
                        MONTH(tranDate) AS month,
                        SUM(contribution) AS total_contribution,
                        SUM(payout) AS total_payout
                        Count(agentId) as total_transaction
                    FROM transactions
                    WHERE agentId = ?
                    GROUP BY YEAR(tranDate), MONTH(tranDate)
                    ORDER BY year DESC, month DESC";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$agentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching monthly transaction summary by agent: " . $e->getMessage());
            return [];
        }
    }

    /**
     * READ: Fetches monthly payout transactions for a specific agent using the 'get_transactionbymonthyear' view.
     *
     * @param int $agentId The agent's ID.
     * @return array An array of associative arrays. Empty array on failure.
     */
    public function getPayoutMonthlyTransaction($agentId) {
        try {
            $sql = "SELECT * FROM get_transactionbymonthyear WHERE agentId = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$agentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching monthly payout transactions by agent: " . $e->getMessage());
            return [];
        }
    }

    /**
     * READ: Fetches all transactions for a specific agent, year, and month from the 'get_all_transaction' view.
     *
     * @param int $agentId The agent's ID.
     * @param int $year The year.
     * @param int $month The month.
     * @return array An array of associative arrays. Empty array on failure.
     */
    public function getAllTransactionsByAgentAndDate($agentId, $year, $month,$type) {
        try {
            $sql = "SELECT * FROM get_all_transaction WHERE agentId = ? AND tranYear = ? AND tranMonth = ? AND tranType =? ";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$agentId, $year, $month, $type]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching all transactions by agent, year, and month: " . $e->getMessage());
            return [];
        }
    }

    /**
     * READ: Fetches all transactions for a specific agent within a given date range from the 'get_all_transaction' view.
     *
     * @param int $agentId The agent's ID.
     * @param string $startDate The start date of the range (e.g., 'YYYY-MM-DD').
     * @param string $endDate The end date of the range (e.g., 'YYYY-MM-DD').
     * @return array An array of associative arrays. Empty array on failure.
     */
    public function getTransactionsByAgentAndDateRange($agentId, $startDate, $endDate) {
        try {
            $sql = "SELECT * FROM get_all_transaction WHERE agentId = ? AND tranDate BETWEEN ? AND ? ORDER BY tranDate DESC, cDate DESC";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$agentId, $startDate, $endDate]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching transactions by agent and date range: " . $e->getMessage());
            return [];
        }
    }


}
?>