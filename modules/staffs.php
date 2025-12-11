<?php
/**
 * Class Agents
 * Handles all CRUD operations for the 'agents' table.
 * Assumes a Database::connect() method returns a valid PDO instance.
 */
class Staffs{

    private $_db;

    public function __construct(){
        $this->_db = Database::connect();
    }

    /**
     * Adds a new agent to the database.
     * @param array $data An associative array of agent data.
     * @return bool True on success, false on failure.
     */
    public function createStaff(array $data): bool {
     
        $sql = "INSERT INTO `staffs` (`agentId`, `staffNum`, `fName`, `lName`, `oName`, `gender`, `dob`, `nationality`, `maritalStatus`, `email`, `mobile`, `postalAddress`, `digitalAdress`, `homeAddress`, `username`, `password`) VALUES (:agentId,:staffNum,:fName,:lName,:oName,:gender,:dob,:nationality,:maritalStatus,:email,:mobile,:postalAddress,:digitalAdress,:homeAddress,:username,:pwd)";
        $stmt = $this->_db->prepare($sql);
        return $stmt->execute([
            ':agentId' => $data['agentId'],
            ':staffNum' => $data['staffNum'],
            ':fName' => $data['firstName'],
            ':lName' => $data['lastName'],
            ':oName' => $data['middleName'], // Corresponds to `oName` in SQL
            ':gender' => $data['gender'],
            ':dob' => $data['dob'],
            ':nationality' => $data['nationality'],
            ':maritalStatus' => $data['maritalStatus'],
            ':email' => $data['email'],
            ':mobile' => $data['mobile'],
            ':postalAddress' => $data['postalAddress'],
            ':digitalAddress' => $data['digitalAddress'], // Corresponds to `digitalAdress` in SQL
            ':homeAddress' => $data['contactAddress'], // Corresponds to `homeAddress` in SQL
            ':username' => $data['username'],
            ':pwd' => $data['password']
        ]);
    }

    /**
     * Retrieves a single staff member by their ID.
     * @param int $staffId The ID of the staff member to retrieve.
     * @return array|null An associative array of staff data if found, null otherwise.
     */
    public function getStaff(int $staffId): ?array {
        $sql = "SELECT * FROM `staffs` WHERE `staffId` = :staffId";
        try {
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([':staffId' => $staffId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error getting staff: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Retrieves a single staff member by their ID.
     * @param int $agentId The ID of the agent to retrieve.
     * @return array|null An associative array of staff data if found, null otherwise.
     */
    public function getStaffByAgentId(int $agentId): ?array {
        $sql = "SELECT * FROM `staffs` WHERE `agentId` = :agentId";
        try {
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([':agentId' => $agentId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error getting staff by agentId: " . $e->getMessage());
            return null;
        }
    }   

    /**
     * Retrieves all staff members, with optional limit and offset for pagination.
     * @param int|null $limit The maximum number of staff members to return.
     * @param int|null $offset The number of staff members to skip from the beginning.
     * @return array An array of associative arrays, each representing a staff member.
     */
    public function getAllStaffs(?int $limit = null, ?int $offset = null): array {
        $sql = "SELECT * FROM `staffs`";
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            if ($offset !== null) {
                $sql .= " OFFSET :offset";
            }
        }

        try {
            $stmt = $this->_db->prepare($sql);
            if ($limit !== null) {
                $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
                if ($offset !== null) {
                    $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
                }
            }
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error getting all staffs: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Updates an existing staff member's information.
     * @param int $staffId The ID of the staff member to update.
     * @param array $data An associative array of staff data to update.
     * @return bool True on success, false on failure.
     */
    public function updateStaff(int $staffId, array $data): bool {
        $sql = "UPDATE `staffs` SET  `fName` = :fName, `lName` = :lName, `oName` = :oName, `gender` = :gender, `dob` = :dob, `nationality` = :nationality, `email` = :email, `mobile` = :mobile, `postalAddress` = :postalAddress, `digitalAdress` = :digitalAdress, `homeAddress` = :homeAddress WHERE `staffId` = :staffId";

        try {
            $stmt = $this->_db->prepare($sql);
            return $stmt->execute([
                ':fName' => $data['fName'],
                ':lName' => $data['lName'],
                ':oName' => $data['oName'],
                ':gender' => $data['gender'],
                ':dob' => $data['dob'],
                ':nationality' => $data['nationality'],
                ':email' => $data['email'],
                ':mobile' => $data['mobile'],
                ':postalAddress' => $data['postalAddress'],
                ':digitalAdress' => $data['digitalAdress'],
                ':homeAddress' => $data['homeAddress'],
                ':staffId' => $staffId
            ]);
        } catch (\PDOException $e) {
            error_log("Error updating staff: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Changes the status for a specific staff member.
     * @param int $staffId The ID of the staff member whose status to change.
     * @param string $status The new status for the staff member.
     * @return bool True on success, false on failure.
     */
    public function changeStatus(int $staffId, string $status): bool {
        $sql = "UPDATE `staffs` SET `status` = :status WHERE `staffId` = :staffId";

        try {
            $stmt = $this->_db->prepare($sql);
            return $stmt->execute([
                ':status' => $status,
                ':staffId' => $staffId
            ]);
        } catch (\PDOException $e) {
            error_log("Error changing staff status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes a staff member from the database.
     * @param int $staffId The ID of the staff member to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteStaff(int $staffId): bool {
        $sql = "DELETE FROM `staffs` WHERE `staffId` = :staffId";
        try {
            $stmt = $this->_db->prepare($sql);
            return $stmt->execute([':staffId' => $staffId]);
        } catch (\PDOException $e) {
            error_log("Error deleting staff: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Searches for staff members based on a search term across specified columns.
     * @param string $searchTerm The term to search for.
     * @param array $searchColumns An array of column names to search within.
     * @param int|null $limit The maximum number of results to return.
     * @param int|null $offset The number of results to skip.
     * @return array An array of associative arrays, each representing a matching staff member.
     */
    public function searchStaffs(string $searchTerm, array $searchColumns = [], ?int $limit = null, ?int $offset = null): array {
        if (empty($searchColumns)) {
            // Default search columns if none provided
            $searchColumns = ['fName', 'lName', 'email', 'mobile', 'username'];
        }

        $whereParts = [];
        $values = [];
        foreach ($searchColumns as $column) {
            $whereParts[] = "`{$column}` LIKE :search{$column}";
            $values[":search{$column}"] = '%' . $searchTerm . '%';
        }

        $sql = "SELECT * FROM `staffs` WHERE " . implode(" OR ", $whereParts);

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            if ($offset !== null) {
                $sql .= " OFFSET :offset";
            }
        }

        try {
            $stmt = $this->_db->prepare($sql);
            foreach ($values as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            if ($limit !== null) {
                $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
                if ($offset !== null) {
                    $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
                }
            }
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error searching staffs: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Filters staff members based on provided criteria.
     * @param array $filters An associative array where keys are column names and values are the filter criteria.
     * @param int|null $limit The maximum number of results to return.
     * @param int|null $offset The number of results to skip.
     * @return array An array of associative arrays, each representing a matching staff member.
     */
    public function filterStaffs(array $filters, ?int $limit = null, ?int $offset = null): array {
        if (empty($filters)) {
            return $this->getAllStaffs($limit, $offset); // No filters, get all
        }

        $whereParts = [];
        $values = [];
        foreach ($filters as $column => $value) {
            // Basic equality filter. Can be extended for other operators (>, <, LIKE)
            $whereParts[] = "`{$column}` = :filter{$column}";
            $values[":filter{$column}"] = $value;
        }

        $sql = "SELECT * FROM `staffs` WHERE " . implode(" AND ", $whereParts);

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            if ($offset !== null) {
                $sql .= " OFFSET :offset";
            }
        }

        try {
            $stmt = $this->_db->prepare($sql);
            foreach ($values as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            if ($limit !== null) {
                $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
                if ($offset !== null) {
                    $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
                }
            }
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error filtering staffs: " . $e->getMessage());
            return [];
        }
    }
}