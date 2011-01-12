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
            $arrParams = $this -> getRequest() -> getParams();
            $testId = $this -> getRequest() -> getParam('testId');
            if ($testId != '') {
                // выбираем из базы категории вопросов
                $objCategories = new QuestionCategories();
                $arrCategories = $objCategories -> getCategoryShortListByTestId( $testId );
                $objForm -> setCategoriesSelectOptions( $arrCategories );
            }
            if ($this->getRequest ()->isPost ()){
                if ( $objForm->isValid ( $_POST )) {
                    // Выполняем update (insert/update данных о вопросе)
                    $strQuestionText = $objForm -> questionText -> getValue();
                    $intQuestionWeight = $objForm -> questionWeight -> getValue();

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
                    $objQuestion -> setWeight( $intQuestionWeight );
                    if ( $arrCategories ) {
                        $objQuestion -> setCategoryId(
                            $objForm -> categoryId -> getValue() );
                    }

                    if (array_key_exists( 'answer', $arrParams) ) {
                        $intAnswerAmount = sizeof ( $arrParams['answer'] );
                        $objQuestion -> setAnswerAmount( $intAnswerAmount);
                    }
                    $objQuestion -> save();
                    if ( !$questionId ) {
                        $questionId = $objQuestions -> getAdapter()-> lastInsertId();
                    }

                    // Вносим в базу варианты ответов и обг=новляем их количество,
                    // поскольку не валидные в базу не добавляются и количество
                    // элементов в массиве $arrParams['answer'] может не совпадать
                    // с количеством ответов, фактически внесенных в БД
                    if (array_key_exists( 'answer', $arrParams)  &&
                            !empty( $arrParams['answer'] ) ) {
                        $intAnswerAmount = $objQuestions ->
                            saveAnswerList($questionId, $arrParams['answer'] );
                        $objQuestion -> setAnswerAmount( $intAnswerAmount);
                    }
                    $objQuestion -> updateRightAnswersAmount();
                    $objQuestion -> save();
                    
                    $this->_helper->redirector ( 'edit', 'test', null,
                        array( 'testId' => $testId ) );
                } else {
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
                        array( 'questionText'   => $objQuestion -> tq_text,
                               'questionId'     => $objQuestion -> tq_id,
                               'questionWeight' => $objQuestion -> getWeight(),
                               'categoryId'     => $objQuestion -> getCategoryId(),
                               'testId'         => $testId));
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