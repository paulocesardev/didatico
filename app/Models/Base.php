<?php

namespace App\Models;

abstract class Base
{
    static $DB;
    protected $table;
    protected $primaryKey = 'id';

    static function initializeDB()
    {
        $conf = PATH_APP . 'Config/database.php';
        if (!file_exists($conf))
            return false;
        $arr = (include $conf);
        $drive = $arr['default'];
        $row = $arr[$drive];
        static::$DB = new \PDO("$drive:host={$row['host']};dbname={$row['dbname']}", $row['user'],  $row['password']);
        static::$DB->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return true;
    }

    static function beginTransaction()
    {
        return static::$DB->beginTransaction();
    }

    static function commit()
    {
        return static::$DB->commit();
    }

    static function rollBack()
    {
        return static::$DB->rollBack();
    }

    static function inTransaction()
    {
        return static::$DB->inTransaction();
    }

    public function query(...$args)
    {
        return static::$DB->query(...$args);
    }

    public function getTable()
    {
        return $this->table;
    }

    function insert(array $data)
    {
        $query  = 'INSERT INTO ' . $this->table;
        $query .= ' (' . implode(', ', array_keys($data)) . ')';
        $query .= ' VALUES(' . trim(str_repeat('?,', count($data)), ',') . ')';

        return static::$DB->prepare($query)->execute(array_values($data));
    }

    function update($where, array $data)
    {
        $params =  array_values($data);
        $query = 'UPDATE ' . $this->table . ' SET ';
        $query .= implode(' = ? , ', array_keys($data)) . '  = ?';
        if (is_numeric($where)) {
            $query .= ' WHERE ' . $this->primaryKey . ' = ?';
            $params[] = $where;
        } elseif (is_array($where)) {
            $query .= ' WHERE ' . implode(' = ? , ', array_keys($where)) . '  = ?';
            array_push($params, ...array_values($where));
        } else {
            $query .= ' WHERE ' . $where;
        }
        return static::$DB->prepare($query)->execute($params);
    }

    function save(array $data)
    {
        if (isset($data[$this->primaryKey])) {
            $w = $data[$this->primaryKey];
            unset($data[$this->primaryKey]);
            return $this->update($w, $data);
        }

        return $this->insert($data);
    }
}
