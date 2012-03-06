<?php

class Loginza
{

    protected $token = 'cfdba68d5c7f4e692c77c636a9fbfac7';
    protected $id = '9042';

    function __construct()
    {

    }

    function Initialize()
    {
        global $Core;

        if ($Core->Vars['method'] == "loginza_form")
        {
            $this->InsertStyle('form');
            $Core->C('Core', 'InsertJS', array('http://loginza.ru/js/widget.js'));
            $Core->C('Init', 'AddStage', array(4, array('Class' => $Core->Plugins[get_class($this)], 'Method' => 'Output', 'Params' => NULL)));
        }
        elseif ($Core->Vars['method'] == "loginza_token" && $Core->Vars['token'] != "")
        {
            $LoginzaAPI = new LoginzaAPI();
            $sig = md5($Core->Vars['token'].$this->token);
            $UserProfile = $LoginzaAPI->getAuthInfo(array('token' => $Core->Vars['token'], 'id' => $this->id, 'sig' => $sig));

            if (!empty($UserProfile->error_type))
            {
                $Core->C('Core', 'LogAdd', array('plugin', 'LOGINZA: '.$UserProfile->error_type.": ".$UserProfile->error_message));
            }
            elseif (empty($UserProfile))
            {
                $Core->C('Core', 'LogAdd', array('plugin', 'LOGINZA: Temporary error.'));
            }
            else
            {
                $_SESSION['loginza']['is_auth'] = 1;
                $_SESSION['loginza']['profile'] = $UserProfile;
            }
        }

        //$Core->add_stage(4, '$this->AddMenuItem(0, array(\'url\' => \'/?action=quit\', \'name\' => \'Выход\'));');
    }

    function Install()
    {

    }

    function Uninstall()
    {

    }

    function Activate()
    {

    }

    function Add()
    {

    }

    function Update()
    {

    }

    function Delete()
    {

    }

    function InsertStyle($Type)
    {
        global $Core;
        /*
          $status = $Core->C('Core', 'InsertStyle', array(
          '.forField {font-family:"Century Gothic";color:#333;font-size:10pt;}
          table.form_cell label {font-size:10pt;color:#555;}
          table.form_cell td {padding-right: 10px;padding-bottom: 10px}
          table.form_cell input[type="text"], table.form_cell input[type="password"],
          table.form_cell input[type="file"], table.form_cell textarea {color:#333;font-size:12pt;margin:5px 0 0 0;padding:0px;font-weight:normal;}
          table.form_cell textarea {padding:5px;}
          form input[type="text"], form input[type="file"], form input[type="password"] {font-size:32pt;margin:10px 0px;padding:5px 5px;}
          form input[type="submit"] {font-size:11pt;margin:10px 0px;padding:5px 15px;}
          nobr.roundField input[type="text"], nobr.roundField input[type="file"], nobr.roundField input[type="password"], div.roundField input[type="text"], div.roundField input[type="password"] {border:0px;margin:0px;height:31px;line-height:31px;background-image:url("/img/round_field_bg.gif");background-repeat:repeat-x;background-color:#e7ecf0;}
          nobr.roundField, div.roundField {display:inline-block;height:31px;padding-left:10px;background-image:url("/img/round_field_left.gif");background-repeat:no-repeat;background-position:left top;margin:5px 0 0 0;}
          nobr.roundField img, div.roundField img {line-height:31px;}')); */
    }

    function Output()
    {
        global $Core;

        $Core->Content['content'][] = array('title' => '', 'item' => $status = $Core->C('Output', 'LoadComponent', array('loginza')));
    }
}

?>