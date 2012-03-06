<?php

class Test
{

    function __construct()
    {
        global $Core;

        if (!$this->CheckInstall())
        {
            $this->Install();
        }
        else
        {
            //$Core->add_stage(3, '$this->LogAdd($this->Plugins[\'Test\']->TestMethod());');
        }
    }

    public function Initialize()
    {

    }

    function Install()
    {

    }

    function UnInstall()
    {

    }

    function Activate()
    {

    }

    function CheckInstall()
    {
        return true;
    }

    function TestMethod()
    {
        //return 'Test method SUCCESS';
    }
}

?>