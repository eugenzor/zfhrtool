<?php
/**
 * @package zfhrtool
 */
    defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

    defined('UPLOAD_PATH')
        || define('UPLOAD_PATH', realpath(dirname(__FILE__) . '/../../upload'));

    defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', 'testing');

    defined('APPLICATION_LOAD_TESTDATA')
        || define('APPLICATION_LOAD_TESTDATA', true);

    set_include_path(implode(PATH_SEPARATOR, array(
        realpath(APPLICATION_PATH . '/../library'),
        get_include_path(),
    )));

    require_once "Zend/Loader/Autoloader.php";
    Zend_Loader_Autoloader::getInstance()->registerNamespace('Zht_')
        ->registerNamespace('PHPUnit_')
        ->pushAutoloader(new Zht_Loader(APPLICATION_PATH . '/models'));