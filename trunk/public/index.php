<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define path to application directory
defined('UPLOAD_PATH')
    || define('UPLOAD_PATH', realpath(dirname(__FILE__) . '/../upload'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

require_once "Zend/Loader/Autoloader.php";
Zend_Loader_Autoloader::getInstance()->registerNamespace('Zht_')
    ->pushAutoloader(new Zht_Loader(APPLICATION_PATH . '/models'));

// Create application, bootstrap, and run
$application = new Zht_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

/*
$front = Zend_Controller_Front::getInstance();

$acl = new Acl();
$front->registerPlugin( new Zht_Controller_Plugin_Acl( $acl ) );
*/

$application->bootstrap()->run();
