<?php

class Cache
{

    public $s = false;
    protected $link;

    public function __construct()
    {
        $this->link = new Memcache();
        ($this->link->pconnect('127.0.0.1', 11211)) ? $this->s = true : $this->s = false;
    }

    public function add()
    {
        $args = func_get_args();
        $this->link->add($args[0], $args[1], $args[2], $args[3]);
    }

    public function get($var)
    {
        return $this->link->get($var);
    }
}

?>