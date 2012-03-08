<?php

class DB
{

    protected $Config = array(
        'db_user' => 'paranoide',
        'db_pass' => 'ROiYG0w5HaAZwONdHopB',
        'db_host' => 'localhost',
        'db_base' => 'paranoide_db',
        'db_char' => 'utf8',
        'db_port' => '3306',
        'db_drvr' => 'mysql');
    
    protected $link;
    protected $stmt;
    protected $query;
    protected $type;
    public $error;

    function __construct()
    {
        (!$this->link) ? $this->connect() : false;
    }

    protected function connect()
    {
        switch ($this->Config['db_drvr'])
        {
            case 'mysql': {
                    $this->link = new PDO('mysql:host='.$this->Config['db_host'].';dbname='.$this->Config['db_base'], $this->Config['db_user'], $this->Config['db_pass']);
                    break;
                }
        }

        $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->link->exec('SET NAMES '.$this->Config['db_char']);
    }

    function e()
    {
        $args = func_get_args();
        return call_user_func_array(array($this, 'execute'), $args);
    }

    function execute()
    {
        global $Core;
        $args = func_get_args();

        ($this->query['WHERE'] == "" && $this->type == "s") ? $this->w() : true;

        foreach ($this->query as $key => $value)
        {
            $sql .= $value;
        }

        if ($this->type == "s")
        {
            $key = md5($sql.print_r($args, 1));

            $cache = (CACHE) ? $Core->cache->get($key) : false;
            $usecache = ($cache) ? true : false;

            if (!$usecache)
            {
                $Core->C('Core', 'LogAdd', array('DB', 'DB: '.$sql));
                $this->stmt = $this->link->prepare($sql);
                $this->stmt->execute($args);

                $cache = $this->fetch();

                (CACHE) ? $Core->cache->add($key, $cache, false, 120) : false;
            }
            else
            {
                $Core->C('Core', 'LogAdd', array('CACHE', 'CACHE: '.$key));
            }

            return $cache;
        }
        elseif ($this->type == "i")
        {
            $this->stmt = $this->link->prepare($sql);
            return $this->stmt->execute($args);
        }
    }

    function s()
    {
        $args = func_get_args();
        return call_user_func_array(array($this, 'select'), $args);
    }

    function select()
    {
        $args = func_get_args();

        unset($this->query);

        $this->query['SELECT'] = 'SELECT ';
        $this->type = 's';
        $size = sizeof($args);

        if ($size == 0)
            $this->query['SELECT'] .= '* ';

        for ($i = 0; $i <= $size - 1; $i++)
        {
            ($i == sizeof($args) - 1) ? $this->query['SELECT'] .= '`'.$args[$i].'` ' : $this->query['SELECT'] .= '`'.$args[$i].'`, ';
        }

        return $this;
    }

    function i()
    {
        $args = func_get_args();
        return call_user_func_array(array($this, 'insert'), $args);
    }

    function insert()
    {
        $args = func_get_args();

        unset($this->query);

        $this->query['INSERT'] = 'INSERT INTO `'.$args[0].'` ';
        $this->type = 'i';

        return $this;
    }

    function v()
    {
        $args = func_get_args();
        return call_user_func_array(array($this, 'values'), $args);
    }

    function values()
    {
        $args = func_get_args();

        $this->query['VALUES'] = 'VALUES ( '.$args[0].' )';

        return $this;
    }

    function update($sql)
    {
        $this->query = $sql;
        return $this;
    }

    function f()
    {
        $args = func_get_args();
        return call_user_func_array(array($this, 'from'), $args);
    }

    function from()
    {
        $args = func_get_args();

        (sizeof($args) == 1) ? $this->query['FROM'] = 'FROM '.$args[0].' ' : $this->error .= "from is WRONG<br>\n";

        return $this;
    }

    function w()
    {
        $args = func_get_args();
        return call_user_func_array(array($this, 'where'), $args);
    }

    function where()
    {
        $args = func_get_args();

        (sizeof($args) == 1) ? $this->query['WHERE'] = 'WHERE '.$args[0].' ' : $this->query['WHERE'] = 'WHERE 1 ';

        return $this;
    }

    function o()
    {
        $args = func_get_args();
        return call_user_func_array(array($this, 'order'), $args);
    }

    function order()
    {
        $args = func_get_args();

        (sizeof($args) == 1) ? $this->query['ORDER'] = 'ORDER BY '.$args[0].' ' : true;

        return $this;
    }

    function l()
    {
        $args = func_get_args();
        return call_user_func_array(array($this, 'limit'), $args);
    }

    function limit()
    {
        $args = func_get_args();

        (sizeof($args) == 2) ? $this->query['LIMIT'] = 'LIMIT '.$args[0].', '.$args[1].' ' : true;

        return $this;
    }

    function fetch()
    {
        if ($this->stmt)
        {
            $this->stmt->setFetchMode(PDO::FETCH_ASSOC);

            while ($row = $this->stmt->fetch())
            {
                ($this->stmt->fetchColumn == 1) ? $res = $row : $res[] = $row;
            }

            $this->stmt->closeCursor();

            return $res;
        }
        else
        {
            return false;
        }
    }
}

?>