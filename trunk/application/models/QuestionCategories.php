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
class QuestionCategories extends Zht_Db_Table
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'test_question_category';

    /**
     * Row Class
     * @var string
     */
    protected $_rowClass = 'QuestionCategory';

    /**
     * Get QuestionCategory By categoryId
     *
     * @param string $categoryId
     * @return QuestionCategory | boolean
     */
    public function getCategoryById($categoryId)
    {
        $where = array (
                'tqc_id=?' => $categoryId );
        $objCategory = $this->fetchRow ( $where );
        if (is_null ( $objCategory )) {
            return false;
        }
        return $objCategory;
    }

    /**
     * Get list (array) of question categories for specified testId
     * Return:
     * array( 1 => array('id'    => '...',
     *                   'name'  => '...',
     *                   'descr' => '...'),
     *        2 => array(...),
     *        ...
     *        )
     *        
     * @param int $testId
     * @return array | boolean
     */
    public function getCategoryListByTestId($testId)
    {
        $select = $this -> select();
        $select ->from( $this, array('id'    => 'tqc_id',
                                     'name'  => 'tqc_name',
                                     'descr' => 'tqc_descr') );
        $select -> where( "t_id = ?", $testId );
        $arrCategory = $this -> fetchAll($select) -> toArray();

        if (is_null ( $arrCategory )) {
            return false;
        }
        return $arrCategory;
    }

    /**
     * Get short list (array) of question categories for specified testId
     * Return:
     * array( id1 => name1,
     *        id2 => name2,
     *        ...
     *        idn => namen
     *       )
     *
     * @param int $testId
     * @return array | boolean
     */
    public function getCategoryShortListByTestId($testId)
    {
        $select = $this -> select();
        $select ->from( $this, array('tqc_id', 'tqc_name') );
        $select -> where( 't_id = ?', $testId );
        $arrCategory = $this -> fetchPairs($select);

        return $arrCategory;
    }
    
    /**
     * Remove question category By Id
     *
     * @param string $categoryId
     * @return boolean
     */
    public function removeCategoryById($categoryId)
    {
        $query = "SELECT count(tq_id) from test_question "
               . "WHERE tqc_id = $categoryId";
        $intQuestionAmount = $this -> getAdapter() ->fetchOne( $query );
        if ( $intTestAmount > 0 ) {
            throw new Exception ( '[LS_CATEGORY_HAS_QUESTIONS]' );
            return false;
        }
        $where = array (
                'tqc_id=?' => $categoryId );
        $intResult = $this -> delete( $where );
        return $intResult;
    }
    
    /**
     * Удаление категорий, соответсвующих заданному тесту (по $testId )
     *
     * @param int $testId
     * @return void
     */
    public function removeCategoriesByTestId( $testId )
    {
        $where = array('t_id=?' => $testId);
        $this -> delete( $where );
    }
    
}
