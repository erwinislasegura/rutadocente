<?php
namespace App\Core;
use PDO;
final class Database {
    private static ?PDO $pdo=null;
    public static function connection(): PDO {
        if(self::$pdo) return self::$pdo;
        $local=dirname(__DIR__,2).'/config/database.local.php';
        $c=file_exists($local)?require $local:require dirname(__DIR__,2).'/config/database.php';
        $dsn="mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset={$c['charset']}";
        return self::$pdo=new PDO($dsn,$c['username'],$c['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
    }
}

