<?php
require_once __DIR__ . '/../exceptions/QueryException.php';

class QueryBuilder
{

    /**
     * @param var $connection
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function findAll(string $table, string $classEntity)
    {
        $sql = "SELECT * FROM $table";
        try{
            $pdoStatment = $this->connection->prepare($sql);
            $pdoStatment->execute();
            $pdoStatment->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $classEntity);
            return $pdoStatment->fetchAll();
        }catch(\PDOException $pdoException){
            throw new QueryException('No se ha podido ejecutar la consulta solicitada: ' . $pdoException->getMessage());
        }
    }
}