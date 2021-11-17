<?php
require_once __DIR__ . '/../exceptions/QueryException.php';
require_once __DIR__ . '/Connection.php';
require_once __DIR__ . '/../core/App.php';
require_once __DIR__ . './../entity/Entity.php';

abstract class QueryBuilder
{
    /**
     * @param var $connection
     */
    private $connection;

    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    private $classEntity;

    public function __construct(string $table, string $classEntity)
    {
        $this->connection =  App::get('connection');
        $this->table = $table;
        $this->classEntity = $classEntity;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM $this->table";
        try {
            $pdoStatement = $this->connection->prepare($sql);
            $pdoStatement->execute();
            $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classEntity);
            return $pdoStatement->fetchAll();
        } catch (\PDOException $pdoException) {
            throw new QueryException('No se ha podido ejecutar la consulta solicitada: ' . $pdoException->getMessage());
        }
    }

    /**
     * @param Entity $entity
     * @throws QueryException
     */

    public function save(Entity $entity)
    {
        try {
            $parameters = $entity->toArray();
            $sql = sprintf(
                'INSERT INTO %s ($s) values (%s)',
                $this->table,
                implode(', ', array_keys($parameters)),
                ':' . implode(', :', array_keys($parameters))
            );

            $statement = $this->connection->prepare($sql);
            $statement->execute($parameters);
        } catch (\PDOException $pdoException) {
            throw new QueryException("Error al insertar en la base de datos: " . $pdoException->getMessage());
        }
    }
}