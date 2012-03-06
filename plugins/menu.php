<?php

class Menu
{

    function __construct()
    {

    }

    function Initialize()
    {
        global $Core;

        $Core->C('Init', 'AddMethod', array(get_class($this), 'AddMenuItem', 'AddMenuItem'));
        $Core->C('Init', 'AddMethod', array(get_class($this), 'CreateMenuLevel', 'CreateMenuLevel'));
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

    function Edit()
    {

    }

    function AddMenuItem($Level, $Params)
    {
        global $Core;
        $Core->Content['menu'][$Level]['children'][] = $Params;
    }

    function CreateMenuLevel($Title)
    {
        global $Core;
        $Core->Content['menu'][]['title'] = $Title;
    }

    function GetLastMenuId($Level)
    {
        global $Core;
        return sizeof($Core->Content['menu'][$Level]['children']) - 1;
    }

    function Output()
    {
        //global $Core;
    }
}

?>