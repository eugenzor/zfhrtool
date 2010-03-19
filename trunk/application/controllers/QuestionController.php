<?php
/**
 * @package zfhrtool
 * @subpackage Controller
 */


/**
 * Контроллер вопросов
 *
 * Обеспечивает работу с вопросами к тестам
 * @package zfhrtool
 * @subpackage Controller
 */

class QuestionController extends Controller_Action_Abstract
{

    /**
     * Инициализация контроллера
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
//        Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
    }

    public function editAction()
    {
        $smarty = Zend_Registry::get('smarty');

        $arrParams = $this->getRequest()->getParams();
        $testId = $this->getRequest()->getParam('testId');
        if ($testId != '') {
            // выбираем из базы данные о редактируемом тесте
            $objTests = new Tests ( );
            $objTest = $objTests->getTestById( $testId );

            $smarty -> assign ( 'objTest', $objTest);
        }

        if (array_key_exists('questionId', $arrParams)  &&
                !empty($arrParams['questionId'])) {
            $questionId = ( int ) $arrParams['questionId'];

            // выбираем из базы данные о редактируемом вопросе
            $objQuestions = new Questions();
            $objQuestion = $objQuestions->getQuestionById( $questionId );
            $arrAnswer = $objQuestions ->
                getAnswerListByQuestionId( $questionId );
            $smarty -> assign ( 'arrAnswer', $arrAnswer);

            $smarty -> assign ( 'objQuestion', $objQuestion);
        }
        $smarty -> display('question_edit.tpl');
    }

    public function updateAction()
    {
        $arrParams = $this->getRequest()->getParams();
        try {
            if (array_key_exists('testId', $arrParams)  &&
                    !empty($arrParams['testId'])) {
                $testId = ( int) $arrParams['testId'];
            } else {
                throw new Exception ( '[LS_REQUIRED_PARAM_FAILED]' );
            }

            if (array_key_exists('questionText', $arrParams)  &&
                    !empty($arrParams['questionText'])) {
                $strQuestionText =
                        strip_tags( trim( $arrParams['questionText'] ) );
            } else {
                throw new Exception ( '[LS_REQUIRED_PARAM_FAILED]' );
            }

            $objQuestions = new Questions ();
            $query = 'SELECT max(tq_sort_index) from mg_test_question';
            $intMaxSortIndex = $objQuestions -> getAdapter() ->
                fetchOne( $query );

            if (array_key_exists('questionId', $arrParams) &&
                    !empty($arrParams['questionId'])) {
                $questionId = ( int ) $arrParams['questionId'];
                $objQuestion = $objQuestions -> getQuestionById( $questionId );
            } else {
                $objQuestion = $objQuestions -> createRow();
                $questionId = null;
            }

            $objQuestion -> setText( $strQuestionText );
            $objQuestion -> setTestId( $testId );
            $objQuestion -> setSortIndex( $intMaxSortIndex + 1 );
            if (array_key_exists('answer', $arrParams) ) {
                $intAnswerAmount = sizeof( $arrParams['answer'] );
                $objQuestion -> setAnswerAmount( $intAnswerAmount);
            }

            $objQuestion -> save();
            if (array_key_exists('answer', $arrParams)  &&
                    !empty($arrParams['answer'])) {
                $objQuestions -> saveAnswerList($questionId,
                    $arrParams['answer']);
            }

        } catch ( Exception $e ){ print $e -> getMessage(); }

        $this->_helper->redirector ( 'edit', 'test', null,
            array( 'testId' => $testId ) );
    }

    public function removeAction()
    {
        $objQuestions = new Questions ();

        $arrParams = $this->getRequest()->getParams();

    try{
        if (array_key_exists('questionId', $arrParams) &&
                !empty($arrParams['questionId'])) {
            $objQuestions -> removeQuestionById($arrParams['questionId']);
        }
    } catch ( Exception $e ){ print $e -> getMessage(); }

        if (array_key_exists('testId', $arrParams)  &&
                !empty($arrParams['testId'])) {
            $testId = ( int) $arrParams['testId'];
            $this->_helper -> redirector ( 'edit', 'test', null,
                array( 'testId' => $testId ) );
        } else {
            $this->_helper -> redirector ( 'index', 'test' );
        }
    }

    public function upAction()
    {
        $arrParams = $this->getRequest()->getParams();
        if (array_key_exists('questionId', $arrParams)  &&
                !empty($arrParams['questionId'])) {
            $questionId = ( int ) $arrParams['questionId'];
        } else {
            throw new Exception ( '[LS_REQUIRED_PARAM_FAILED]' );
        }

        $objQuestions = new Questions();
        $objQuestions -> moveQuestionUp( $questionId );
        

        if (array_key_exists('testId', $arrParams)  &&
                !empty($arrParams['testId'])) {
            $testId = ( int ) $arrParams['testId'];
            $this->_helper->redirector ( 'edit', 'test', null,
                array( 'testId' => $testId ) );
        } else {
            $this->_helper->redirector ( 'index', 'test' );
        }
    }

    public function downAction()
    {
        $arrParams = $this->getRequest()->getParams();
        if (array_key_exists('questionId', $arrParams)  &&
                !empty($arrParams['questionId'])) {
            $questionId = ( int ) $arrParams['questionId'];
        } else {
            throw new Exception ( '[LS_REQUIRED_PARAM_FAILED]' );
        }

        $objQuestions = new Questions();
        $objQuestions -> moveQuestionDown( $questionId );


        if (array_key_exists('testId', $arrParams)  &&
                !empty($arrParams['testId'])) {
            $testId = ( int ) $arrParams['testId'];
            $this->_helper->redirector ( 'edit', 'test', null,
                array( 'testId' => $testId ) );
        } else {
            $this->_helper->redirector ( 'index', 'test' );
        }
    }
}