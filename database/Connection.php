<?php

require_once __DIR__ . './../core/App.php';

class Connection
{
    public static function make()
    {
        try{
            //Fijar conexión en UTF8
            //Fijar que cuando se produzca un error salte una excepcion
            $config = App::get('config')['database'];
            
            $opciones = [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true
            ];
            $connection = new PDO(
                'mysql:host=localhost;dbname=proyecto1;charset=utf8',
                'proyecto1',
                'sa',
                $opciones
            );
        }catch(PDOException $PDOException){
            die($PDOException->getMessage());
        }
        return $connection;
    }
}