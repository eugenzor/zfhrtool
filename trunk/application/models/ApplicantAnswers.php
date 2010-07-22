<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Соискатель ( строка таблицы )
 *
 * @package zfhrtool
 * @subpackage Model
 */
class ApplicantAnswers extends Zht_Db_Table
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'applicant_answers';

    /**
     * Имя таблицы, которое изпользуеться при join
     * @var string
     */
    const  NAME = 'applicant_answers';

    /**
     * Возвращает вариатны ответов на вопросы, которые дал соискатель
     *
     * @param integer $applicantTestId Id пройденого тестирования
     * @return array вариатны ответов на вопросы
     */
    public function getAnswers($applicantTestId)
    {
        $answers = $this -> getAdapter() ->query($this-> getAdapter()-> select()
                            -> from( array('atq' => $this->_name), array('*'))
                            -> join( array('tqa' => Answers::NAME), 'atq.answer_id = tqa.tqa_id', array('tqa_text','tq_id'))
                            -> where('atq.applicant_tests_id = ?', $applicantTestId))
                -> fetchAll();
        return $answers;
    }

}
