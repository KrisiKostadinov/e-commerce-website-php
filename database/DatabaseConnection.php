<?php

class DatabaseConnection
{
    private $connection;

    public function __construct($host, $db_name, $username, $password)
    {
        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_AUTOCOMMIT, false);  // За поддръжка на транзакции
        } catch (PDOException $e) {
            Response::serverError("Грешка при свързване:", $e->getMessage())->send();
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function close()
    {
        $this->connection = null;
    }

    // Transaction management
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    public function commit()
    {
        return $this->connection->commit();
    }

    public function rollBack()
    {
        return $this->connection->rollBack();
    }

    public function getLastInsertedId()
    {
        return $this->connection->lastInsertId();
    }

    // Generic CRUD operations

    // Create - Insert query
    public function create($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    // Read - Select query
    public function read($table, $conditions = [], $columns = "*")
    {
        $sql = "SELECT $columns FROM $table";
        
        if (!empty($conditions)) {
            $conditionStrings = [];
            foreach ($conditions as $key => $value) {
                $conditionStrings[] = "$key = :$key";
            }
            $sql .= " WHERE " . implode(" AND ", $conditionStrings);
        }

        $stmt = $this->connection->prepare($sql);

        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update - Update query
    public function update($table, $data, $conditions)
    {
        $setParts = [];
        foreach ($data as $key => $value) {
            $setParts[] = "$key = :$key";
        }
        $setString = implode(", ", $setParts);

        $conditionStrings = [];
        foreach ($conditions as $key => $value) {
            $conditionStrings[] = "$key = :condition_$key";
        }
        $conditionString = implode(" AND ", $conditionStrings);

        $sql = "UPDATE $table SET $setString WHERE $conditionString";
        $stmt = $this->connection->prepare($sql);

        // Bind the update data
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        // Bind the conditions
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":condition_$key", $value);
        }

        return $stmt->execute();
    }

    // Delete - Delete query
    public function delete($table, $conditions)
    {
        $conditionStrings = [];
        foreach ($conditions as $key => $value) {
            $conditionStrings[] = "$key = :$key";
        }
        $conditionString = implode(" AND ", $conditionStrings);

        $sql = "DELETE FROM $table WHERE $conditionString";
        $stmt = $this->connection->prepare($sql);

        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }
}