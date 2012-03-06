<?php

class Console
{

    public $RequireRights;
    protected $reverse = 1;
    protected $show_console = 1;

    function __construct()
    {

    }

    public function Initialize()
    {
        global $Core;

        $Core->C('Menu', 'AddMenuItem', array('Level' => 1, 'Params' => array('url' => '/?method=console', 'name' => 'Колнсоль')));

        $Core->C('Init', 'AddMethod', array(get_class($this), 'console_update', 'ConsoleUpdate'));

        //if(SHOW_CONSOLE == 1)
        //{
        $this->InsertStyle();
        //$Core->add_stage(7,  '$this->Plugins['.get_class($this).']->ShowConsole();');
        //if(is_object($Core->Plugins[get_class($this)])) echo 1111;
        $Core->C('Init', 'AddStage', array(7, array('Class' => $Core->Plugins[get_class($this)], 'Method' => 'ShowConsole', 'Params' => NULL)));
        //sprint_r($Core->Stages);
        //}
    }

    public function Install()
    {

    }

    public function Uninstall()
    {

    }

    public function Activate()
    {

    }

    protected function InsertStyle()
    {
        global $Core;

        $Core->C('Init', 'InsertStyle', array('body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }'));
        /*
         * div.console{position:relative;margin:5px 0;padding:2px 4px 2px 4px;overflow:hidden;zoom:1;background-color:#cce0f5;overflow:hidden;-moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px}
          div.console .content{font-weight:bold;font-size:13px;}
          div.console div.log{ float:left; height: 100px; width: 99%; font-family:"Century Gothic";background-color:#ffffff;font-size:9pt; margin: 0px; padding: 3px; font-weight:normal; overflow: auto; border:1px solid #999999;}
          div.console table.log{ width: 100%; margin: 2px;}
          div.console span.box{ margin-right: 6px;}
          div.console input.box{ margin: 0;}

          .tab1{ padding-right: 12px;}
          .tab2{ padding-right: 24px;}
          .tab3{ padding-right: 36px;}
          .tab4{ padding-right: 48px;}
         *
         */
    }

    public function ConsoleUpdate()
    {
        global $Core;

        unset($Core->_SESSION['console']);

        if (is_array($Core->Vars['console']['log']))
            foreach ($Core->Vars['console']['log'] as $key => $value)
            {
                if ($value == 1)
                {
                    $Core->_SESSION['console']['log'][$key] = 'checked';
                }
            }

        if (is_array($Core->Vars['console']['var']))
            foreach ($Core->Vars['console']['var'] as $key => $value)
            {
                if ($value == 1)
                {
                    $Core->_SESSION['console']['var'][$key] = 'checked';
                }
            }

        if ($Core->_SERVER['HTTP_REFERER'] == "")
        {
            $Core->C('Output', 'Redirect', array('/'));
        }
        else
        {
            $Core->C('Output', 'Redirect', array($Core->_SERVER['HTTP_REFERER']));
        }
    }

    public function ShowConsole()
    {
        global $Core;

        $Core->C('Output', 'LoadComponent', array('console'));
        $Core->Content['console'] = array(
            // Settings
            'path' => 'http://'.$Core->_SERVER['HTTP_HOST'].$Core->_SERVER['PHP_SELF'].'?method=console_update',
            // Content[log]
            'full' => $Core->_SESSION['console']['log']['full'],
            'vars' => $Core->_SESSION['console']['log']['variable'],
            'sql' => $Core->_SESSION['console']['log']['SQL'],
            'plugin' => $Core->_SESSION['console']['log']['plugin'],
            'include' => $Core->_SESSION['console']['log']['include'],
            'methods' => $Core->_SESSION['console']['log']['methods'],
            'output' => $Core->_SESSION['console']['log']['output'],
            // Content[variables]
            'session' => $Core->_SESSION['console']['var']['session'],
            'vmethods' => $Core->_SESSION['console']['var']['methods'],
            'stages' => $Core->_SESSION['console']['var']['stages'],
            'config' => $Core->_SESSION['console']['var']['config'],
            'vplugins' => $Core->_SESSION['console']['var']['plugins'],
            'vvars' => $Core->_SESSION['console']['var']['vars'],
            'voutput' => $Core->_SESSION['console']['var']['output'],
            'vcontent' => $Core->_SESSION['console']['var']['content'],
            'vtemplate' => $Core->_SESSION['console']['var']['template'],
            'actions' => $Core->_SESSION['console']['var']['actions']
        );

        if (is_array($Core->Log))
            if ($this->reverse == 1)
            {
                for ($i = sizeof($Core->Log) - 1; $i >= 0; $i--)
                {
                    if ($Core->_SESSION['console']['log'][$Core->Log[$i]['type']] == 'checked' || $Core->_SESSION['console']['log']['full'] == 'checked')
                    {
                        $Core->Content['log'] .= $Core->Log[$i]['time'].': '.$Core->Log[$i]['mess']."<br>\n";
                    }
                }
            }
            else
            {
                for ($i = 0; $i <= sizeof($Core->Log) - 1; $i++)
                {
                    if ($Core->_SESSION['console']['log'][$Core->Log[$i]['type']] == 'checked' || $Core->_SESSION['console']['log']['full'] == 'checked')
                    {
                        $Core->Content['log'] .= $Core->Log[$i]['time'].': '.$Core->Log[$i]['mess']."<br>\n";
                    }
                }
            }

        if (is_array($Core->_SESSION['console']['var']))
            foreach ($Core->_SESSION['console']['var'] as $key => $val)
            {
                if ($val == 'checked')
                {
                    switch ($key)
                    {
                        case 'session' : {
                                $var = print_r($Core->_SESSION, 1);
                                break;
                            }
                        case 'methods' : {
                                $var = print_r($Core->Methods, 1);
                                break;
                            }
                        case 'stages' : {
                                $var = print_r($Core->Stages, 1);
                                break;
                            }
                        case 'config' : {
                                $var = print_r($Core->Config, 1);
                                break;
                            }
                        case 'plugins' : {
                                $var = print_r($Core->AccessPlugins, 1);
                                break;
                            }
                        case 'vars' : {
                                $var = print_r($Core->Vars, 1);
                                break;
                            }
                        case 'output' : {
                                $var = print_r($Core->Output, 1);
                                break;
                            }
                        case 'content' : {
                                $var = print_r($Core->Content, 1);
                                break;
                            }
                        case 'template' : { /* $var = print_r($Core->Template, 1); */
                                break;
                            }
                        case 'actions' : {
                                $var = print_r($Core->Actions, 1);
                                break;
                            }
                    }

                    $var = str_replace("\n", '<br>'."\n", $var);
                    $var = str_replace("                ", '<span class=tab4></span>', $var);
                    $var = str_replace("            ", '<span class=tab3></span>', $var);
                    $var = str_replace("        ", '<span class=tab2></span>', $var);
                    $var = str_replace("    ", '<span class=tab1></span>', $var);

                    $Core->Content['variables'] .= '<b>'.$key.'</b> = '.$var;
                }
            }
    }
}
