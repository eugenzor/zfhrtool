<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Комментарий ( строка таблицы )
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Comment extends Zend_Db_Table_Row_Abstract {
    /**
     * Constructor.
     *
     * @param  int $commentId optional
     * @return void
     */
    public function __construct(array $config = array(), $commentId = null)
    {
        parent :: __construct($config);
        if ( $commentId ) {
            $this->id = $commentId;
        }
    }

}