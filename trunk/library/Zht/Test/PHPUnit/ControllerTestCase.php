<?php
/**
 * @package zfhrtool
 * @subpackage library
 */

/**
 * Дополнение к Zend_Test_PHPUnit_ControllerTestCase
 *
 * Класс дополняет стандартный Zend_Test_PHPUnit_ControllerTestCase
 * до возможности предварительно заргужать дампы
 * @package zfhrtool
 * @subpackage library
 */
abstract class Zht_Test_PHPUnit_ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    protected $_dbDump;
    protected $_configPath = '/configs/application.ini';
    protected static $_resetOnce;

    /**
     * Выполняем начальную загрузку дампа, если есть
     *
     * @return void
     */
    protected function setUp()
    {
        $application = new Zht_Application(APPLICATION_ENV, APPLICATION_PATH . $this->_configPath);
        $this->bootstrap = array($application->getBootstrap(), 'bootstrap');

        parent::setUp();
    }


    /**
     * Добавлено закрытие mysql-соединения при сбросе
     *
     * @return void
     */
    public function reset()
    {
        parent::reset();
        $db = Zend_Db_Table_Abstract::getDefaultAdapter ();
        if ($db instanceof Zend_Db_Adapter_Abstract){
            $db->closeConnection();
        }
    }

    /**
     * Исправлено принудительное выставление throwException в false
     *
     * @param  string|null $url
     * @return void
     */
    public function dispatch($url = null)
    {
        // redirector should not exit
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $redirector->setExit(false);

        // json helper should not exit
        $json = Zend_Controller_Action_HelperBroker::getStaticHelper('json');
        $json->suppressExit = true;

        $request    = $this->getRequest();
        if (null !== $url) {
            $request->setRequestUri($url);
        }
        $request->setPathInfo(null);
        $controller = $this->getFrontController();
        $this->frontController
             ->setRequest($request)
             ->setResponse($this->getResponse())
             ->returnResponse(false);
        $this->frontController->dispatch();
    }
    
    /**
     * Переустановка дампа
     *
     * @return bool
     */
    public function resetDb()
    {
        if (!is_file($this->_dbDump)){
            throw new Exception('Нет такого файла ' . $this->_dbDump);
        }

        if($db = Zend_Db_Table_Abstract::getDefaultAdapter ()){
            $sqls = explode(";\n", file_get_contents($this->_dbDump));
            foreach($sqls as $sql) {
                if ($sql = trim($sql)) {
                    $db->query($sql);
                }
            }
            unset($sqls);
            return true;
        }
        
        return false;
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
        $resetKey = md5(get_class($this).$file);
        if(empty(self::$_resetOnce[$resetKey])){
            self::$_resetOnce[$resetKey] = $this->resetDb();
        }
        
        return $this;
    }



}
