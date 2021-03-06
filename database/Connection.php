<?php
class  Connection
{
    public static function make($config)
    {
        try {
            //fijar conexion en UTF8
            //fijar que cuando se produzca un error salte una excepcion
            $connection = new PDO(
                $config['connection'] . ';dbname=' . $config['name'],
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $PDOException) {
            die($PDOException->getMessage());
        }
        return $connection;
    }
}