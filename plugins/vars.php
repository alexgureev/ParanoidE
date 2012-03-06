<?php

class Variables
{

    function __construct()
    {

    }

    function Initialize()
    {
        global $Core;

        $Core->C('Init', 'AddMethod', array(get_class($this), 'list', 'Output'));
    }

    function Install()
    {
        global $Core;

        /*
         * Добавление таблиц и данных в бд
         */

        $Core->C('Core', 'sql_query', array('SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO"; SET time_zone = "+00:00";
		CREATE TABLE `variables` (
		  `id` int(11) NOT NULL auto_increment,
		  `name` varchar(128) collate utf8_unicode_ci NOT NULL,
		  `method` varchar(128) collate utf8_unicode_ci NOT NULL,
		  `type` varchar(8) collate utf8_unicode_ci NOT NULL,
		  `lenght` int(11) NOT NULL,
		  `default` varchar(128) collate utf8_unicode_ci NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;
		INSERT INTO `variables` (`id`, `name`, `method`, `type`, `lenght`, `default`) VALUES
		(1, \'module\', \'GET\', \'string\', 0, \'frontpage\'),
		(2, \'id\', \'GET\', \'int\', 3, \'0\'),
		(3, \'method\', \'GET\', \'string\', 32, \'output_content\'),
		(4, \'action\', \'GET\', \'string\', 32, \'\');'));
    }

    function Uninstall()
    {
        global $Core;

        /*
         * Удаление таблицы и дополнительных данных в бд
         */

        $Core->C('Core', 'sql_query', array('DROP TABLE `variables`'));
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
        global $Core;

        $Core->C('Core', 'delete', array('variables', '`id`="'.$Core->Vars['id'].'"'));
    }

    function Edit()
    {

    }

    function Output()
    {
        global $Core;

        if ($Core->Vars['id'] == 0)
        {
            $Core->C('Menu', 'CreateMenuLevel', 'Variables');
            $Core->C('Menu', 'AddMenuItem', array(0, array('url' => 'http://yandex.ru', 'name' => 'Yandex')));
            /*
              $Core->Content['content'][0]['item'] = 'Some content from plugin, id==0';
              $Core->Content['content'][0]['title'] = 'Some title';
             */
        }
        else
        {
            //$Core->Content = 'Some content from plugin, id!=0';
        }
    }
}

?>