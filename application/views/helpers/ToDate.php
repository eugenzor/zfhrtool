<?php
/**
 * @package zfhrtool
 * @subpackage View
 */


/**
 * Хелпер даты
 *
 * Хелпер для перевода строки в дату определенного формата
 * @package zfhrtool
 * @subpackage View
 */
class Helper_ToDate
{
    /**
     * Переводит строку datestamp, datetime из формата mysql в dd.MM.yyy
     *
     * @param $date строка даты
     * @param bool $showTime показывать время
     * @return string дата в необходимом формате 
     */    
    public function toDate($date, $showTime = false) {
        $d = new Zend_Date( $date);
        if ($showTime)
            return $d->toString('dd.MM.yyyy, HH:mm');
        return $d->toString('dd.MM.yyyy');
    }

}
?>