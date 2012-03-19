<?php

include_once (INIT_PATH.'core.php');
include_once (INIT_PATH.'config.php');
include_once (INIT_PATH.'plugins.php');
include_once (INIT_PATH.'input.php');
include_once (INIT_PATH.'output.php');

function __autoload($Class)
{
    include_once LIB_PATH.$Class.'.class.php';
}

class Init extends Output
{

    protected $Stages;
    protected $Stage = 0;
    protected $Methods;
    protected $Actions;
    protected $DefaultMethods;
    protected $Log;
    protected $Config;
    protected $Vars;
    protected $Redirect;
    protected $Output;
    protected $Content;
    protected $Template;
    protected $Plugins;
    protected $DB;
    protected $db;
    protected $AccessPlugins;
    protected $_GET;
    protected $_ENV;
    protected $_POST;
    protected $_FILES;
    protected $_COOKIE;
    protected $_SERVER;
    protected $_REQUEST;
    protected $_SESSION;
    protected $GLOBALS;
    public $cache;

    function __construct($Config)
    {
        $this->Config = $Config;
        Output :: __construct();
        $this->LogAdd('service', 'Output init.');
        $this->SetDefaultCfg($Config);
    }

    public function __set($Name, $Value)
    {
        if ($this->CheckRights($this->GetTrace()))
        {
            $this->{$Name} = $Value;
        }
    }

    public function &__get($Name)
    {
        if ($this->CheckRights($this->GetTrace()))
        {
            return $this->{$Name};
        }
    }

    protected function AddStage($Level, $Array)
    {
        if ($this->Stage > $Level)
        {
            $this->LogAdd('service', 'L_ERROR: Stage('.$Level.') already done, can\'t eval: "'.print_r($Array, 1).'"');
        }
        else
        {
            $this->Stages[$Level][] = $Array;
        }
    }

    protected function ExecuteStage($Stages)
    {
        foreach ($Stages as $Stage)
        {
            call_user_func_array(array($Stage['Class'], $Stage['Method']), array());
        }
    }

    protected function ExecuteStages()
    {
        ksort($this->Stages);

        for ($i = $this->Stage; $i <= 9; $i++)
        {
            if (sizeof($this->Stages[$i]) > 0)
            {
                $this->LogAdd('service', 'Stage: '.$i.' start.');
                $this->ExecuteStage($this->Stages[$i]);
            }

            $this->Stage = $i;
        }

        $this->SaveLog();
/*
        $bcrypt = new Bcrypt(5);

        $hash = $bcrypt->hash('password');
        $isGood = $bcrypt->verify('password', $hash);

        print_r($hash);
        print_r($isGood);
*/
        echo 123;
    }

    public function Run()
    {
        $this->ExecuteStages();
    }

    function AddMethod($Class, $Action, $Method)
    {
        $this->Methods[$Class][$Action] = $Method;
        $this->Actions[$Action] = array('class' => $Class, 'method' => $Method);
    }

    public function C($Class, $Method, $Params)
    {
        if ($this->CheckRights($this->GetTrace()))
        {
            if ($this->Config['base_classes'][$Class])
            {
                return call_user_func_array(array($this, $Method), $Params);
            }
            else
            {
                return call_user_func_array(array($this->Plugins[$Class], $this->Methods[$Class][$Method]), $Params);
            }
        }
    }

    protected function CallToAction($Action)
    {
        if ($this->Actions[$Action])
        {
            call_user_func_array(array($this->Plugins[$this->Actions[$Action]['class']], $this->Actions[$Action]['method']), array());
        }
    }

    protected function SetDefaultMethod($Class, $Type, $Method)
    {
        // Переделать структуру переменной
        $this->DefaultMethods[$Class][$Type] = $Method;
    }

    protected function CallDefaultMethod($Class, $Type, $Params)
    {
        if ($this->DefaultMethods[$Class][$Type])
        {
            // Переделать вызов метода с учетом измененной переменной, добавить проверку вызываемого метода и возвращаемый результат
            call_user_func_array(array($this->DefaultMethods[$Class][$Type]), $Params);
        }
    }
}

?>