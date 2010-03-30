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
    }

    /**
     * Добавление/обновление вопроса
     * @return void
     */
    public function editAction()
    {
        if ( $this -> _authorize( 'question', 'edit')) {
            $objForm = new Form_Question_Edit();
            if ($this->getRequest ()->isPost ()){
                if ( $objForm->isValid ( $_POST )) {
                    // Выполняем update (insert/update данных о вопросе)
                    $arrParams = $this->getRequest()->getParams();
                    $testId = $objForm -> testId -> getValue();
                    $strQuestionText = $objForm -> questionText -> getValue();

                    $objQuestions = new Questions ();
                    $intMaxSortIndex =
                            $objQuestions -> getMaxSortIndex( $testId );

                    $questionId = $objForm -> questionId -> getValue();
                    if ( !empty( $questionId)) {
                        $objQuestion = $objQuestions ->
                            getQuestionById( $questionId );
                    } else {
                        $objQuestion = $objQuestions -> createRow();
                        $questionId = null;
                        $objQuestion -> setSortIndex( $intMaxSortIndex + 1 );
                    }


                    $objQuestion -> setText( $strQuestionText );
                    $objQuestion -> setTestId( $testId );

                    if (array_key_exists( 'answer', $arrParams) ) {
                        $intAnswerAmount = sizeof ( $arrParams['answer'] );
                        $objQuestion -> setAnswerAmount( $intAnswerAmount);
                    }
                    $objQuestion -> save();

                    // Вносим в базу варианты ответов и обг=новляем их количество,
                    // поскольку не валидные в базу не добавляются и количество
                    // элементов в массиве $arrParams['answer'] может не совпадать
                    // с количеством ответов, фактически внесенных в БД
                    if (array_key_exists( 'answer', $arrParams)  &&
                            !empty( $arrParams['answer'] ) ) {
                        $intAnswerAmount = $objQuestions ->
                            saveAnswerList($questionId, $arrParams['answer'] );
                        $objQuestion -> setAnswerAmount( $intAnswerAmount);
                        $objQuestion -> save();
                    }

                    $this->_helper->redirector ( 'edit', 'test', null,
                        array( 'testId' => $testId ) );
                } else {
                    $arrParams = $this->getRequest() -> getParams();
                    $testId = $objForm -> testId -> getValue();
                    if ($testId != '') {
                        // выбираем из базы данные о редактируемом тесте
                        $objTests = new Tests ( );
                        $objTest = $objTests->getTestById( $testId );
                        $this -> view -> objTest = $objTest;
                    }

                    $arrAnswer = array();
                    if (array_key_exists('questionId', $arrParams)  &&
                            !empty($arrParams['questionId'])) {
                        $questionId = ( int ) $arrParams['questionId'];

                        // выбираем из базы данные о редактируемом вопросе
                        $objQuestions = new Questions();
                        $objQuestion = $objQuestions -> getQuestionById( $questionId );
                        $arrAnswer = $objQuestions ->
                            getAnswerListByQuestionId( $questionId );
                    }
                    $objForm -> addAnswersSubForm( $arrAnswer );
                    // @todo: пререформатировать массив answer, полученный через POST для функции addAnswersSubForm()
                }
            } else {
                $arrParams = $this->getRequest() -> getParams();
                $testId = $this -> getRequest() -> getParam('testId');
                $arrAnswer = array();
                if (array_key_exists('questionId', $arrParams)  &&
                        !empty($arrParams['questionId'])) {
                    $questionId = ( int ) $arrParams['questionId'];

                    // выбираем из базы данные о редактируемом вопросе
                    $objQuestions = new Questions();
                    $objQuestion = $objQuestions -> getQuestionById( $questionId );
                    $arrAnswer = $objQuestions ->
                        getAnswerListByQuestionId( $questionId );
                    $objForm -> populate(
                        array( 'questionText'       => $objQuestion -> tq_text,
                               'questionId'         => $objQuestion -> tq_id,
                               'testId'             => $testId));
                }
                $objForm -> addAnswersSubForm( $arrAnswer );
                if ( !empty( $testId ) ) {
                    // выбираем из базы данные о редактируемом тесте
                    $objTests = new Tests ( );
                    $objTest = $objTests->getTestById( $testId );

                    $this -> view -> objTest = $objTest;
                    $objForm -> populate( array( 'testId' => $testId));
                }
            }
            $this -> view -> objForm = $objForm;
        }
    }

    /**
     * Удаление вопроса
     * @return void
     */
    public function removeAction()
    {
        if ( $this -> _authorize( 'question', 'remove')) {
            $objQuestions = new Questions ();

            $arrParams = $this->getRequest()->getParams();

            if (array_key_exists('questionId', $arrParams) &&
                    !empty($arrParams['questionId'])) {
                $objQuestions -> removeQuestionById($arrParams['questionId']);
            }

            if (array_key_exists('testId', $arrParams)  &&
                    !empty($arrParams['testId'])) {
                $testId = ( int) $arrParams['testId'];
                $this->_helper -> redirector ( 'edit', 'test', null,
                    array( 'testId' => $testId ) );
            } else {
                $this->_helper -> redirector ( 'index', 'test' );
            }
        }
    }

    /**
     * Уменьшение индекса сортировка для вопроса (перемещение вверх в списке)
     * @return void
     */
    public function upAction()
    {
        if ( $this -> _authorize( 'question', 'up')) {
            $arrParams = $this->getRequest()->getParams();
            if (array_key_exists('questionId', $arrParams)  &&
                    !empty($arrParams['questionId'])) {
                $questionId = ( int ) $arrParams['questionId'];
            } else {
                throw new Exception ( '[LS_REQUIRED_PARAM_FAILED]' );
            }
            if (array_key_exists('testId', $arrParams)  &&
                    !empty($arrParams['testId'])) {
                $testId = ( int ) $arrParams['testId'];
            } else {
                throw new Exception ( '[LS_REQUIRED_PARAM_FAILED]' );
            }

            $objQuestions = new Questions();
            $objQuestions -> moveQuestionUp( $questionId, $testId );


            if (array_key_exists('testId', $arrParams)  &&
                    !empty($arrParams['testId'])) {
                $this->_helper->redirector ( 'edit', 'test', null,
                    array( 'testId' => $testId ) );
            }
        }
    }

    /**
     * Увеличение индекса сортировка для вопроса (перемещение вниз в списке)
     * @return void
     */
    public function downAction()
    {
        if ( $this -> _authorize( 'question', 'down')) {
            $arrParams = $this->getRequest()->getParams();
            if (array_key_exists('questionId', $arrParams)  &&
                    !empty($arrParams['questionId'])) {
                $questionId = ( int ) $arrParams['questionId'];
            } else {
                throw new Exception ( '[LS_REQUIRED_PARAM_FAILED]' );
            }
            if (array_key_exists('testId', $arrParams)  &&
                    !empty($arrParams['testId'])) {
                $testId = ( int ) $arrParams['testId'];
            } else {
                throw new Exception ( '[LS_REQUIRED_PARAM_FAILED]' );
            }

            $objQuestions = new Questions();
            $objQuestions -> moveQuestionDown( $questionId, $testId );


            if (array_key_exists('testId', $arrParams)  &&
                    !empty($arrParams['testId'])) {
                $this->_helper->redirector ( 'edit', 'test', null,
                    array( 'testId' => $testId ) );
            }
        }
    }
}