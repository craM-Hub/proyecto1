<?php
require_once __DIR__ . '/../entity/Usuario.php';
require_once __DIR__ . '/../database/QueryBuilder.php';

class UsuarioRepository extends QueryBuilder
{
    protected $passwordGenerator;
    public function __construct(IPasswordGenerator $passwordGenerator)
    {
        $this->passwordGenerator = $passwordGenerator;
        parent::__construct('users', 'Usuario');
    }
    /* 
    public function save(Entity $entity)
    {
        try {
            $parameters = $entity->toArray();
            $parameters['password'] = $this->passwordGenerator::encrypt($parameters['password']);
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
    } */

    //REFACTORIZADA
    public function save(Entity $entity)
    {
        $parameters = $entity->toArray();
        $entity->setPassword($this->passwordGenerator::encrypt($parameters['password']));
        parent::save($entity);
    }

    public function findByUserNameAndPassword(string $username, string $password): Usuario
    {
        $sql = "SELECT * FROM $this->table WHERE username = :username";
        $parameters = [
            'username' => $username
        ];

        $statement = $this->connection->prepare($sql);
        $statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classEntity);
        $statement->execute($parameters);
        $result = $statement->fetch();

        if (empty($result)) {
            throw new NotFoundException("No se ha encontrado ningún elemento con esas credenciales");
        } else {
            if (!$this->passwordGenerator::passwordVerify($password, $result->getPassword())) {
                throw new NotFoundException("No se ha encontrado ningún elemento con esas credenciales");
            }
        }
        return $result;
    }
}
