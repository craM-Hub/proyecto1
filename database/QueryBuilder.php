<?php
require_once __DIR__ . '/../exceptions/QueryException.php';
require_once __DIR__ . '/Connection.php';
require_once __DIR__ . '/../core/App.php';
require_once __DIR__ . '/../entity/Entity.php';

abstract class QueryBuilder
{
    /**
     * @param var $connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $classEntity;

    public function __construct(string $table, string $classEntity)
    {
        $this->connection =  App::get('connection');
        $this->table = $table;
        $this->classEntity = $classEntity;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM $this->table";
        return $this->executeQuery($sql);
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
                'INSERT INTO %s (%s) values (%s)',
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

    public function executeQuery(string $sql)
    {
        try {
            $pdoStatement = $this->connection->prepare($sql);
            $pdoStatement->execute();
            $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classEntity);
            return $pdoStatement->fetchAll();
        } catch (\PDOException $pdoException) {
            throw new QueryException('No se ha podido ejecutar la consulta solicitada: ' . $pdoException->getMessage());
        }
    }

    public function findById(int $id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = $id";
        $result = $this->executeQuery($sql);
        if (empty($result)) {
            throw new NotFoundException("No se ha encontrado ning??n elemento con id $id");
        }
        return $result[0];
    }

    public function executeTransaction(callable $fnExecuteQuerys)
    {
        try {
            $this->connection->beginTransaction();
            $fnExecuteQuerys();
            $this->connection->commit();
        } catch (\PDOException $pdoException) {
            $this->connection->rollBack();
            throw new QueryException("No se ha podido realizar la operaci??n: " . $pdoException->getMessage());
        }
    }

    /**
     * @param array $parameters
     * return string
     */
    private function getUpdate(array $parameters): string
    {
        $updates = "";
        foreach ($parameters as $key => $value) {
            if ($key !== 'id') {
                if ($updates !== '') {
                    $updates .= ', ';
                }
                $updates .= $key . '=:' . $key;
            }
        }
        return $updates;
    }

    /**
     * @param Entity $entity
     * @throws QueryException
     */
    public function update(Entity $entity)
    {
        try {
            $parameters = $entity->toArray();
            $sql = sprintf(
                'UPDATE %s SET %s WHERE id = :id',
                $this->table,
                $this->getUpdate($parameters)
            );
            $statement = $this->connection->prepare($sql);
            $statement->execute($parameters);
        } catch (\PDOException $pdoException) {
            throw new QueryException("Error al actualizar el elemento con id {$parameters['id']}: " . $pdoException->getMessage());
        }
    }
    /*     public function findByUserNameAndPassword(string $username, string $password): Usuario
    {
        $sql = "SELECT * FROM $this->table WHERE username = :username AND password = :password";
        $parameters = [
            'username' => $username,
            'password' =>
            $this->passwordGenerator::encrypt($password)
        ];

        try {
            $pdoStatement = $this->connection->prepare($sql);
            $pdoStatement->execute($parameters);
            $pdoStatement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->classEntity);
            $result = $pdoStatement->fetch();
            if (empty($result)) {
                throw new NotFoundException('No se ha encontrado ning??n usuario con esas credenciales');
            }
            return $result;
        } catch (PDOException $pdoException) {
            throw new QueryException('No se ha podido ejecutar la consulta solicitada');
        }
        return null;
    } */
}
