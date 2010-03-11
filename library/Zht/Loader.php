<?php
/**
 * @package zfhrtool
 * @subpackage library
 */



/**
 * Загрузчик
 *
 * Обеспечивает автозагрузку классов, в т.ч. и модели без префикса Model_
 * @package zfhrtool
 * @subpackage library
 */
class Zht_Loader implements Zend_Loader_Autoloader_Interface
{
    protected static $_defaultDirectory = '';
    protected $_directory;
    protected $_instance = null;
 
    public function __construct($directory = null)
    {
        if (empty($directory)) {
            $this->_directory = self::$_defaultDirectory;
        } elseif(is_dir($directory) OR is_array($directory)){ 
            $this->_directory = $directory;
        } else {
            throw new Zend_Exception("Нет такой директории $directory!");
        }
    }
 
    public function autoload($class)
    {
        Zend_Loader::loadClass($class, $this->_directory);
    }
    
    public static function getDefaultDirectory()
    {
        return self::$_defaultDirectory;
    }
    
    public static function setDefaultDirectory($directory)
    {
        if(is_dir($directory) OR is_array($directory)){
            self::$_defaultDirectory = $directory;
        } else {
            throw new Zend_Exception("Нет такой директории $directory!");
        }
    }
    
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function setDirectory($directory)
    {
        if(is_dir($directory) OR is_array($directory)){
            $this->_directory = $directory;
        } else {
            throw new Zend_Exception("Нет такой директории $directory!");
        }
        
        return $this;
    }
}
