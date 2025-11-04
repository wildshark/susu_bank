<?php

/**
 * Class Clients
 * Handles all CRUD operations for the 'clients' table.
 * Assumes a Database::connect() method returns a valid PDO instance.
 */
class ClientsAccount {

    private $_db;

    public function __construct() {
        $this->_db = Database::connect();
    }

    public function createClient($data) {
        $sql = "INSERT INTO clients (
                    agentId, accountNumber, cdate, lmodify, firstName, midName, 
                    lastName, gender, dob, maritalStatus, nationality, 
                    occupation, mobile, email, postalAddress, digitalAddress, 
                    contactAddress, nkName, nkRelationship, nkMobile, 
                    passport, govtIDcard, status
                ) VALUES (
                    ?,?, NOW(), NOW(), ?, ?, 
                    ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, 
                    ?, ?, ?
                )";
        
        $stmt = $this->_db->prepare($sql);
        
        $add = $stmt->execute([
            $data['agentId'],
            $data['accountNumber'],
            $data['firstName'],
            $data['midName'],
            $data['lastName'],
            $data['gender'],
            $data['dob'],
            $data['maritalStatus'],
            $data['nationality'],
            $data['occupation'],
            $data['mobile'],
            $data['email'],
            $data['postalAddress'],
            $data['digitalAddress'],
            $data['contactAddress'],
            $data['nkName'],
            $data['nkRelationship'],
            $data['nkMobile'],
            $data['passport'],
            $data['govtIDcard'],
            $data['status']
        ]);
        if($add == false){
            return false;
        }else{
            return $this->_db->lastInsertId();
        }
    }

    public function getClientById($clientId) {
        $sql = "SELECT * FROM clients WHERE clientId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([$clientId]);
        // fetch() returns the client row as an array, or false if not found.
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllClients() {
        $sql = "SELECT * FROM clients ORDER BY lastName ASC, firstName ASC";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countClientbyAgent($agentId) {
        $sql = "SELECT COUNT(*) FROM clients WHERE agentId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([$agentId]);
        return $stmt->fetchColumn();
    }

    public function getClientsByAgentId($agentId) {
        $sql = "SELECT * FROM clients WHERE agentId = ? ORDER BY firstName ASC";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([$agentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateClient($clientId, $data) {
        $sql = "UPDATE clients SET 
                    lmodify = NOW(),
                    agentId = ?,
                    firstName = ?,
                    midName = ?,
                    lastName = ?,
                    gender = ?,
                    dob = ?,
                    maritalStatus = ?,
                    nationality = ?,
                    occupation = ?,
                    mobile = ?,
                    email = ?,
                    postalAddress = ?,
                    digitalAddress = ?,
                    contactAddress = ?,
                    nkName = ?,
                    nkRelationship = ?,
                    nkMobile = ?,
                    passport = ?,
                    govtIDcard = ?,
                    status = ?
                WHERE clientId = ?";
        
        $stmt = $this->_db->prepare($sql);
        
        return $stmt->execute([
            $data['agentId'],
            $data['firstName'],
            $data['midName'],
            $data['lastName'],
            $data['gender'],
            $data['dob'],
            $data['maritalStatus'],
            $data['nationality'],
            $data['occupation'],
            $data['mobile'],
            $data['email'],
            $data['postalAddress'],
            $data['digitalAddress'],
            $data['contactAddress'],
            $data['nkName'],
            $data['nkRelationship'],
            $data['nkMobile'],
            $data['passport'],
            $data['govtIDcard'],
            $data['status'],
            $clientId
        ]);
    }

    public function deleteClient($clientId) {
        $sql = "DELETE FROM clients WHERE clientId = ?";
        $stmt = $this->_db->prepare($sql);
        return $stmt->execute([$clientId]);
    }

    public function getClientByAccountNumber($accountNumber) {
        $sql = "SELECT * FROM clients WHERE accountNumber = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([$accountNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>
