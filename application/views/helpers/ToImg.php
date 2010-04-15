<?php
/**
 * @package zfhrtool
 * @subpackage View
 */


/**
 * Хелпер для преобразования строки в <img>
 *
 * Хелпер для проверки существования файла изображения
 * @package zfhrtool
 * @subpackage View
 */
class Helper_ToImg
{
    /**
     * Переводит строку $str в тег img, если файл существует
     *
     * @param $img строка
     * @return string строка в необходимом формате 
     */    
    public function toImg($img, $width='', $height='') {
        $validator = new Zend_Validate_File_Exists($_SERVER['DOCUMENT_ROOT'] . '/public/images/');
        if (! $validator -> isValid($img))
          return '';
        return '<img src="/images/' . $img . '"'
            . (($width == '') ? '' : (' width="' . $width . '"'))
            . (($height == '') ? '' : (' height="' . $height . '"'))
            . '>';
    }
}
?>