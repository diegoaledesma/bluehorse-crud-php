<?php

class DB
{
    private $arrAnd     = [];
    private $arrOr      = [];
    private $endQuery   = '';
    private $conf;

    public function connect()
    {
        $this->conf     = require 'config/settings__.php';
        $host           = 'p:'.$this->conf['host'];
        $user           = $this->conf['user'];
        $pass           = $this->conf['pass'];
        $dbName         = $this->conf['db_name'];
        $port           = $this->conf['port'];
        $charset        = $this->conf['charset'];

        if (! $this->dbc = new mysqli($host, $user, $pass, $dbName, $port)) {
            throw new Exception("No se pudo conectar a la base.");
        }

        $this->dbc->set_charset($charset);
        return $this->dbc;
    }

    public function query($query)
    {
        return $this->dbc->query($query);
    }

    public function numRows($result)
    {
        return $result->num_rows;
    }

    public function fetchObject($result)
    {
        return $result->fetch_object();
    }

    public function fetchArray($result)
    {
        return $result->fetch_array(MYSQLI_ASSOC);
    }

    public function realEscapeString($data)
    {
        return $this->dbc->real_escape_string($data);
    }

    public function error()
    {
        return $this->dbc->error;
    }

    public function table($name)
    {
        $this->tableName = $name;
        return $this;
    }

    public function where($field, $op, $value)
    {
        $value = $this->realEscapeString($value);
        $this->end_query .= " WHERE `$field` $op '$value'";
        return $this;
    }

    public function and_where($field, $op, $value)
    {
        $value = $this->realEscapeString($value);
        $this->end_query .= " AND `$field` $op '$value'";
        return $this;
    }

    public function or_where($field, $op, $value, $aux = '')
    {
        $start  = null;
        $end    = null;
        if ($aux != '' && $aux == '(') {
            $start = '(';
        } elseif ($aux != '' && $aux == ')') {
            $end = ')';
        }
        $value = $this->realEscapeString($value);
        $this->end_query .= " OR $start`$field` $op '$value'$end";
        return $this;
    }

    public function store()
    {
        $this->type = 'INSERT INTO';
        return $this;
    }

    public function update()
    {
        $this->type = 'UPDATE';
        return $this;
    }

    public function data($data)
    {
        $arrQuery = [];
        foreach ($data as $name => $value) {
            $value      = $this->realEscapeString($value);
            $arrQuery[] = "`$name` = '$value'";
        }

        $this->end_query = "$this->type `$this->tableName` SET " . join(', ', $arrQuery);
        return $this;
    }

    public function select($data)
    {
        if (is_array($data) && count($data) > 0) {
            $fields = "`" . join("`, `", $data) . "`";
        } else {
            $fields = "*";
        }
        $this->end_query = "SELECT $fields FROM `$this->tableName`";
        return $this;
    }

    public function end_query()
    {
        return $this->dbc->query($this->end_query);
    }
}