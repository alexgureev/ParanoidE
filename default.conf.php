<?php

/*
 * Path configuration
 */

define('INIT_PATH', 	'/home/www/paranoide.ru/www/');
define('PLUGIN_PATH', 	'/home/www/paranoide.ru/www/plugins/');
define('TEMPLATE_PATH', '/home/www/paranoide.ru/www/templates/');
define('LIB_PATH', 	'/home/www/paranoide.ru/www/libs/');

/*
 * Other constants & variables
 */

define('DEBUG', '1');
define('CACHE', '1');
define('USEDB', '1');

$Config['base_classes'] = array('Init' => true, 'Output' => true, 'Core' => true, 'Config' => true, 'Plugins' => true, 'Input' => true, 'DB' => true, 'Cache' => true);
$Config['base_vars'] = array('Stages' => true, 'Methods' => true, 'Stage' => true, 'Actions' => true, 'DefaultMethods' => true, 'Log' => true, 'Config' => true, 'Vars' => true, 'Redirect' => true, 'Output' => true, 'Content' => true, 'Template' => true, 'Plugins' => true);

?>