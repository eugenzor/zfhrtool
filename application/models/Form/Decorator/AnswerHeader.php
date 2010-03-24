<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Декоратор Header для подформы ответов
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_Decorator_AnswerHeader extends Zend_Form_Decorator_Abstract
{
    /**
     * Decorate content and/or element
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        // construct header
        $header = '<div>Варианты ответов :</div>';

        return $header . $content;
    }
}