<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Define path to smarty library directory
defined('SMARTY_DIR')
    || define('SMARTY_DIR', APPLICATION_PATH . '/../library/smarty/');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

require_once "Zend/Loader/Autoloader.php";
Zend_Loader_Autoloader::getInstance()->registerNamespace('Zht_')
    ->pushAutoloader(new Zht_Loader(APPLICATION_PATH . '/models'));

/** Инициализируем Smarty шаблонизатор */
//print "smarty_dir = ".SMARTY_DIR;
require_once SMARTY_DIR . 'Smarty.class.php';
$Smarty = new Smarty();
$Smarty -> debugging        = false;
$Smarty -> force_compile    = true;
$Smarty -> caching          = false;
$Smarty -> compile_check    = true;
$Smarty -> cache_lifetime   = -1;
$Smarty -> template_dir     = APPLICATION_PATH . '/sviews/templates';
$Smarty -> compile_dir      = APPLICATION_PATH . '/sviews/templates_c';
$Smarty -> plugins_dir      = array(
                              SMARTY_DIR . 'plugins', 'resources/
plugins');
/** Добавляем в реестр Smarty */
Zend_Registry::set('smarty', $Smarty);

// Create application, bootstrap, and run
$application = new Zht_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()->run();