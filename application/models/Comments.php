<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Таблица комментариев
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Comments extends Zht_Db_Table
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'comments';

    /**
     * Row Class
     * @var string
     */
    protected $_rowClass = 'Comment';

    /**
     * Get array of Comments By applicantId
     *
     * @param int $applicantId
     * @return arrTest | array 
     */
    public function getComments($applicantId)
    {
        $select = $this -> getAdapter()-> select();
        $select -> from( $this->_name );
        $select -> where( 'c_applicant_id = ?', $applicantId );
        $select -> join( 'users', 'c_user_id = users.id');

        $stmt = $this -> getAdapter() -> query($select);
        $arrComments = $stmt -> fetchAll(Zend_Db::FETCH_OBJ);

        if (is_null ( $arrComments )) {
            return false;
        }
        return $arrComments;
    }

    /**
     * Removes Comment By CommentId
     *
     * @param int $commentId
     * @return void
     */
    public function removeCommentById($commentId)
    {
        $where = array (
                'c_id=?' => $commentId );
        $this -> delete( $where );
    }

    /**
     * Removes Comments By ApplicantId
     *
     * @param int $applicantId
     * @return void
     */
    public function removeCommentsByApplicantId($applicantId)
    {
        $where = array (
                'c_applicant_id=?' => $applicantId );
        $this -> delete( $where );
    }

}