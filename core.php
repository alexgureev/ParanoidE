<?php

class Core
{

    function __construct()
    {
        $this->getStart();
        $this->LogAdd('service', 'Start.');

        $this->AddStage(0, array('Class' => 'Init', 'Method' => 'DBInit', 'Params' => NULL));
        $this->AddStage(1, array('Class' => 'Init', 'Method' => 'get_options', 'Params' => NULL));
        $this->AddStage(1, array('Class' => 'Init', 'Method' => 'LoadPluginsAccessRights', 'Params' => NULL));
    }

    protected function LogAdd($Type, $Log)
    {
        // Добавить разделение лога по типам, добавить всплывающее окно с чекбоксами с возможностью отключать те или иные типы логов
        $size = sizeof($this->Log);

        $t = round($this->getMicrotime() - $this->Config['start_time'], 4);

        $this->Log[$size]['time'] = $t;
        $this->Log[$size]['type'] = $Type;
        $this->Log[$size]['mess'] = $Log;
    }

    protected function getMicrotime()
    {
        return microtime(1);
    }

    protected function getStart()
    {
        $this->Config['start_time'] = $this->getMicrotime();
    }

    protected function DBInit()
    {
        ($this->db = new DB($this->Config)) ? $this->LogAdd('SQL', 'DB: init.') : $this->LogAdd('SQL', 'DB: NOT init.');
    }

    protected function InsertJS($path)
    {
        $this->Content['js'][] = array('path' => $path);
    }

    protected function InsertCSS($path)
    {
        $this->Content['css'][] = array('path' => $path);
    }

    protected function InsertStyle($css)
    {
        $this->Content['style'][] = array('css' => $css);
    }

    protected function Redirect($URI)
    {
        $this->Redirect = $URI;
    }

    protected function GetTrace()
    {
        $exc = new Exception("Trace point");
        $trace = $exc->getTrace();
        return $trace;
    }

    protected function LoadPluginsAccessRights()
    {
        //TODO: Переделать этот говнокод в один SQL-запрос или при помощи новой функции для реверса массивов

        $access = $this->db->s('pid', 'rid', 'rights')->f('access_plugins')->e();
        $plugins = $this->db->s('id', 'class', 'path')->f('plugins')->e();
        $rights = $this->db->s('id', 'rights', 'class', 'function', 'method')->f('access_rights')->e();

        for ($i = 0; $i <= sizeof($plugins) - 1; $i++)
        {
            $classes[$plugins[$i]['id']] = array('class' => $plugins[$i]['class'], 'path' => $plugins[$i]['path']);
        }

        for ($i = 0; $i <= sizeof($rights) - 1; $i++)
        {
            $right[$rights[$i]['id']] = array('rights' => $rights[$i]['rights'], 'class' => $rights[$i]['class'], 'function' => $rights[$i]['function'], 'method' => $rights[$i]['method']);
        }

        for ($i = 0; $i <= sizeof($access) - 1; $i++)
        {
            $this->AccessPlugins[$classes[$access[$i]['pid']]['class']][$right[$access[$i]['rid']]['class']][$right[$access[$i]['rid']]['function']] = $right[$access[$i]['rid']]['rights'].':'.$access[$i]['rights'];
            $this->AccessPlugins[PLUGIN_PATH.$classes[$access[$i]['pid']]['path']][$right[$access[$i]['rid']]['class']][$right[$access[$i]['rid']]['function']] = $right[$access[$i]['rid']]['rights'].':'.$access[$i]['rights'];
        }
    }

    protected function CheckRights($Trace)
    {
        /*
         * 	Для облегчения понимания кода введены короткие, локальные переменные.
         */

        $IsBase = $this->Config['base_classes'][$Trace[2]['class']]; // Is base class?
        $TC = $Trace[1]['class'];         // Target Class
        $TM = $Trace[1]['function'];        // Target Method
        $CC = $Trace[1]['args'][0];         // Called Class
        $CM = $Trace[1]['args'][1];         // Called Method
        $Var = $Trace[1]['args'][0];         // Variable
        $Args = $Trace[1]['args'];         // All arguments
        $SC = $Trace[2]['class'];         // Source Class
        $SM = $Trace[2]['function'];        // Source Method
        $SF = $Trace[2]['file'];         // Source File

        if (($TC == "Init" && $TM == "C") || $IsBase)
        {
            if ($IsBase)
            {
                return true;
            }
            elseif ($this->AccessPlugins[$SC][$CC][$CM])
            {
                // TODO: Переделать проверку прав с учетом результата RIGHTS
                return true;
            }
            else
            {
                $this->LogAdd('service', 'A_ERROR: There are no rights for access to '.$CC.'::'.$CM.' from '.$SF.' (<b>'.$SC.'::'.$SM.'</b>)');
                return false;
            }
        }
        elseif (/* $TC == "Init" && */$TM == "__get")
        {
            if ($this->AccessPlugins[$SC][$TC][$Var])
            {
                return true;
            }
            else
            {
                $this->LogAdd('service', 'A_ERROR: There are no rights for access to '.$TC.'::$'.$Var.' from '.$SF.' (<b>'.$SC.'::'.$SM.'</b>)');
                return false;
            }
        }
        elseif (/* $TC == "Init" && */$TM == "__set")
        {
            if ($this->AccessPlugins[$SC][$TC][$Var])
            {
                return true;
            }
            else
            {
                $this->LogAdd('service', 'A_ERROR: There are no rights for access to '.$TC.'::$'.$Var.' from '.$SF.' (<b>'.$SC.'::'.$SM.'</b>)');
                return false;
            }
        }
        else
        {
            $this->LogAdd('service', 'A_ERROR: Called '.$TC.' from '.$SF.' (<b>'.$SC.'::'.$SM.'</b>)');
        }

        return false;
    }
}

?>