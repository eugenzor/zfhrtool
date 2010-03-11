<?php
/**
 * @package zfhrtool
 * @subpackage library
 */


/**
 * Доработка базового Zend_Application
 * @package zfhrtool
 * @subpackage library
 */
class Zht_Application extends Zend_Application
{
    /**
     * Добавляет конфиг в реестр, сливает с конфигом-патчем (если есть)
     *
     * @param  string $file
     * @throws Zend_Application_Exception When invalid configuration file is provided
     * @return array
     */
    protected function _loadConfig($file)
    {
        $environment = $this->getEnvironment();
        $config = new Zend_Config_Ini($file, $environment, true);

        # Получаем путь к дополнительному конфигу
        $additionConfig = isset($config->config)?$config->config:dirname($file) . '/_' . basename($file);

        if (is_readable($additionConfig)){
            try{
                $configCustom = new Zend_Config_Ini($additionConfig, $environment);
                $config->merge($configCustom);
            }catch(Zend_Config_Exception $e){}
        }

        if (isset($config->config)){
            unset($config->config);
        }
        $config->setReadOnly();
        Zend_Registry::set('config', $config);
        return $config->toArray();
    }
}