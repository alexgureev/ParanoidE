<?php

class AJAX
{

    protected $link;

    public function __construct()
    {

    }

    public function Initialize()
    {
        global $Core;
        $Core->C('Init', 'AddMethod', array(get_class($this), 'ajax_request', 'AJAXRequest'));
    }

    public function AJAXResponce()
    {
        /* global $Core;
          $Core->Content['content']['title'] = '<h3> test title</h3>';
          $Core->Content['content']['item'] = '<div id="ajax_div" class="ajax_div"> test ajax </div> <a class="btn ajaxtest">AJAX</a>';
         */

        global $Core;
        die(print_r($Core->_COOKIE, 1).print_r($Core->_POST, 1));
    }

    public function AJAXRequest()
    {
        $this->AJAXResponce();
    }
}

?>