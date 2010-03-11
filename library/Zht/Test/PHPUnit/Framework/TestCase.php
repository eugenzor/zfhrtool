<?php
/**
 * @package zfhrtool
 * @subpackage library
 */


/**
 * Дополнение к PHPUnit_Framework_TestCase
 *
 * Класс дополняет стандартный PHPUnit_Framework_TestCase
 * до возможности предварительно заргужать дампы
 * @package zfhrtool
 * @subpackage library
 */
abstract class Zht_Test_PHPUnit_Framework_TestCase extends PHPUnit_Framework_TestCase
{
    protected $_dbDump;
    protected $_configPath = '/configs/application.ini';
    protected $_resources;


    /**
     * Выполняем инициализацию нужных нам ресурсов
     *
     * @return void
     */
    protected function setUp()
    {
        $application = new Zht_Application(APPLICATION_ENV, APPLICATION_PATH . $this->_configPath);
        $application->getBootstrap()->bootstrap($this->_resources);
        parent::setUp();
    }

    /**
     * Установить ресурсы
     *
     * @return Zht_Test_PHPUnit_Framework_TestCase
     */
    public function setResources($resources)
    {
        if (!is_array($resources)){
            $resources = array($resources);
        }
        $this->_resources = $resources;
        return $this;
    }


    /**
     * Переустановка дампа
     *
     * @return Zht_Test_PHPUnit_Framework_TestCase
     */
    public function resetDb()
    {
        if (!is_file($this->_dbDump)){
            throw new Exception('Нет такого файла ' . $this->_dbDump);
        }
        $db = Zend_Db_Table_Abstract::getDefaultAdapter ();
        $sqls = explode(";\n", file_get_contents($this->_dbDump));
        foreach($sqls as $sql) {
            if ($sql = trim($sql)) {
                $db->query($sql);
            }
        }
        unset($sqls);
        return $this;
    }

    
    /**
     * Установить путь к файлу, в котором лежит заргузочный дамп
     * @param string $file путь к этому файлу (желательно - абсолютный)
     * @return Zht_Test_PHPUnit_ControllerTestCase
     */
    public function setDbDump($file)
    {
        if (!is_file($file)){
            throw new Exception('Нет такого файла ' . $file);
        }
        $this->_dbDump = $file;
        return $this;
    }

}