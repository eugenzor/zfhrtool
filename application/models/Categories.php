<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Таблица категорий
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Categories extends Zht_Db_Table
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'mg_category';

    /**
     * Row Class
     * @var string
     */
    protected $_rowClass = 'Category';

    /**
     * Get Category By categoryId
     *
     * @param string $categoryId
     * @return Category | boolean
     */
    public function getCategoryById($categoryId)
    {
        $where = array (
                'cat_id=?' => $categoryId );
        $objCategory = $this->fetchRow ( $where );
        if (is_null ( $objCategory )) {
            return false;
        }
        return $objCategory;
    }

    public function getCategoryList()
    {
        $arrCategory = $this -> getAdapter()->
            fetchAll("SELECT * FROM $this->_name");

        if (is_null ( $arrCategory )) {
            return false;
        }
        return $arrCategory;
    }

    public function removeCategoryById($categoryId)
    {
        $query = "SELECT count(t_id) from mg_test "
               . "WHERE cat_id = $categoryId";
        $intTestAmount = $this -> getAdapter() ->fetchOne( $query );
        if ( $intTestAmount > 0 ) {
            throw new Exception ( '[LS_CATEGORY_HAS_TESTS]' );
            return false;
        }
        $where = array (
                'cat_id=?' => $categoryId );
        $intResult = $this -> delete( $where );
        return $intResult;
    }
}

