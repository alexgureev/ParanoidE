<?php

class Input extends Plugins
{

    function __construct()
    {
        Plugins :: __construct();
        $this->LogAdd('service', 'Plugins init.');
        $this->AddStage(1, array('Class' => 'Init', 'Method' => 'GetVars', 'Params' => NULL));
        $this->AddStage(9, array('Class' => 'Init', 'Method' => 'ReturnVars', 'Params' => NULL));
    }

    protected function Load_vars()
    {
        $Vars = $this->db->s()->f('variables')->e();
        return $Vars;
    }

    protected function get_query_string()
    {
        return $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
    }

    protected function GetVars()
    {
        $dVars = $this->Load_vars();
        $qString = $this->get_query_string();

        foreach ($dVars as $Vars)
        {
            switch ($Vars['type'])
            {
                case 'int': {
                        if (is_numeric($GLOBALS['_'.$Vars['method']][$Vars['name']]) && (strlen($GLOBALS['_'.$Vars['method']][$Vars['name']]) <= $Vars['lenght'] || $Vars['lenght'] == 0))
                        {
                            $this->Vars[$Vars['name']] = $GLOBALS['_'.$Vars['method']][$Vars['name']];
                            $this->LogAdd('variable', 'Got variable: (int)$_'.$Vars['method'].'['.$Vars['name'].'] = '.$this->Vars[$Vars['name']]);
                        }
                        elseif (!isset($GLOBALS['_'.$Vars['method']][$Vars['name']]))
                        {
                            $this->LogAdd('variable', 'Default "'.$Vars['name'].'" value.');
                            $this->Vars[$Vars['name']] = $Vars['default'];
                        }
                        else
                        {
                            $this->LogAdd('variable', 'V_ERROR: "'.$Vars['name'].'" has wrong type/size.');
                            $this->Vars[$Vars['name']] = $Vars['default'];
                        }
                        break;
                    }

                case 'string': {
                        if (is_string($GLOBALS['_'.$Vars['method']][$Vars['name']]) && (strlen($GLOBALS['_'.$Vars['method']][$Vars['name']]) <= $Vars['lenght'] || $Vars['lenght'] == 0))
                        {
                            $this->Vars[$Vars['name']] = htmlspecialchars($GLOBALS['_'.$Vars['method']][$Vars['name']]);
                            $this->LogAdd('variable', 'Got variable: (string)$_'.$Vars['method'].'['.$Vars['name'].'] = '.$this->Vars[$Vars['name']]);
                        }
                        elseif (!isset($GLOBALS['_'.$Vars['method']][$Vars['name']]))
                        {
                            $this->LogAdd('variable', 'Default "'.$Vars['name'].'" value.');
                            $this->Vars[$Vars['name']] = $Vars['default'];
                        }
                        else
                        {
                            $this->LogAdd('variable', 'V_ERROR: "'.$Vars['name'].'" has wrong type/size.');
                            $this->Vars[$Vars['name']] = $Vars['default'];
                        }
                        break;
                    }

                case 'array': {
                        if (is_array($GLOBALS['_'.$Vars['method']][$Vars['name']]) && (sizeof($GLOBALS['_'.$Vars['method']][$Vars['name']]) <= $Vars['lenght'] || $Vars['lenght'] == 0))
                        {
                            $this->Vars[$Vars['name']] = $GLOBALS['_'.$Vars['method']][$Vars['name']];
                            $this->LogAdd('variable', 'Got variable: (array)$_'.$Vars['method'].'['.$Vars['name'].'] = '.$this->Vars[$Vars['name']]);
                        }
                        elseif (!isset($GLOBALS['_'.$Vars['method']][$Vars['name']]))
                        {
                            $this->LogAdd('variable', 'Default "'.$Vars['name'].'" value.');
                            $this->Vars[$Vars['name']] = $Vars['default'];
                        }
                        else
                        {
                            $this->LogAdd('variable', 'V_ERROR: "'.$Vars['name'].'" has wrong type/size.');
                            $this->Vars[$Vars['name']] = $Vars['default'];
                        }
                        break;
                    }
            }
        }

        $this->UnsetVars();
    }

    protected function UnsetVars()
    {
        /*
         * Защита от доступа из плагинов, все необходимые переменные находятся в $this
         */

        $this->_GET = $_GET;
        $this->_ENV = $_ENV;
        $this->_POST = $_POST;
        $this->_FILES = $_FILES;
        $this->_COOKIE = $_COOKIE;
        $this->_SERVER = $_SERVER;
        $this->_REQUEST = $_REQUEST;
        $this->_SESSION = $_SESSION;
        $this->GLOBALS = $GLOBALS;

        unset($_GET, $_ENV, $_POST, $_FILES, $_COOKIE, $_SERVER, $_REQUEST, $GLOBALS);
        foreach (array_keys($_SESSION) as $keys)
            unset($_SESSION[$keys]);
    }

    protected function ReturnVars()
    {
        $_SESSION = $this->_SESSION;
    }
}

?>