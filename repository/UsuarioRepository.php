<?php
require_once __DIR__ . '/../entity/Usuario.php';
require_once __DIR__ . '/../database/QueryBuilder.php';

class UsuarioRepository extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct('users', 'Usuario');
    }

    public function findByUserNameAndPassword(string $username, string $password): ?Usuario
    {
        $sql = "SELECT * FROM $this->table WHERE username = :username AND password = :password";
        $parameters = ['username' => $username, 'password' => $password];

        try {
            $pdoStatement = $this->conection->prepare($sql);
            $pdoStatement->execute($parameters);
            $pdoStatement->serFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->classEntity);
            $result = $pdoStatement->fetch();
            if (empty($result)) {
                throw new NotFoundException('No se ha encontrado ningún usuario con esas credenciales');
            }
            return $result;
        } catch (PDOException $pdoException) {
            throw new QueryException('No se ha podido ejecutar la consulta solicitada');
        }
        return null;
    }
}