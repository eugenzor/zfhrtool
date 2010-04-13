<?php
/**
 * @package zfhrtool
 * @subpackage View
 */


/**
 * Хелпер для замены \n, \r, \t
 *
 * Хелпер для замены \n, \r, \t на <br>, &nbsp;
 * @package zfhrtool
 * @subpackage View
 */
class Helper_Special
{
    /**
     * Переводит строку datestamp, datetime из формата mysql в dd.MM.yyy
     *
     * @param $str строка
     * @return string строка в необходимом формате 
     */    
    public function special($str) {
        $str = str_replace("\r\n", "<br>", $str);
        $str = str_replace("\n", "<br>", $str);
        $str = str_replace("\t", str_repeat("&nbsp;", 4), $str);
        return $str;
    }
}
?>