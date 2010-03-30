<?php
/**
 * @package zfhrtool
 * @subpackage Controller
 */


/**
 * Контроллер тестов
 *
 * Обеспечивает работу с тестами
 * @package zfhrtool
 * @subpackage Controller
 */
class TestController extends Controller_Action_Abstract
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
     * Список тестов (главная страница)
     * @return void
     */
    public function indexAction()
    {
        if ( $this -> _authorize( 'test', 'view')) {
            $objFilterForm = new Form_Test_Filter();
            $objCategories = new Categories ();
            $arrCategory = $objCategories -> getCategoryList();
            $objFilterForm -> setFilterSelectOptions( $arrCategory );

            if ($this->getRequest ()->isPost ()) {
                   $arrParams = $this -> _request -> getPost();
                   $categoryId = ( int ) $arrParams['categoryId'];
                   $strTestFilter = !empty( $arrParams['strTestFilter'] )?
                        strip_tags( trim ( $arrParams['strTestFilter'] ) ) : null;
                   $objFilterForm -> populate( $arrParams );
            } else {
                $categoryId = -1;
                $strTestFilter = null;
            }

            $objTests = new Tests();
            $arrTest = $objTests ->
                getTestListByCategoryId($categoryId, $strTestFilter);

            $this -> view -> arrTest = $arrTest;
            $this -> view -> objFilterForm = $objFilterForm;
        }
    }

    /**
     * Добавление/обновление теста
     * @return void
     */
    public function editAction()
    {
        if ( $this -> _authorize( 'test', 'edit')) {
            $objForm = new Form_Test_Edit();
            $objCategories = new Categories ();
            $arrCategory = $objCategories -> getCategoryList();
            $objForm -> setSelectOptions( $arrCategory );

            if ($this->getRequest ()->isPost ()){
                if ( $objForm->isValid ( $_POST )) {
                    // Выполняем update (insert/update данных о категории)
                    $objTests = new Tests ();

                    $testId = $objForm -> testId -> getValue();
                    if ( !empty($testId)) {
                        $objTest = $objTests -> getTestById( $testId );
                    } else {
                        $testId = null;
                        $objTest = $objTests -> createRow();
                    }

                    $testName = $objForm -> testName -> getValue();
                        $objTest->setName ( $testName );
                    $objTest->setCategoryId (
                        $objForm -> categoryId -> getValue() );
                    $objTest->setQuestionAmount ( ( int )
                        $objForm -> testQuestionAmount -> getValue() );
                    $objTest -> save();

                    if ( 'questionAdd' == $objForm -> formAction -> getValue() ) {
                        if (!$testId) {
                            $testId = $objTests -> getAdapter()-> lastInsertId();
                        }
                        $this->_helper -> redirector ( 'edit', 'question', null,
                            array( 'testId' => $testId ) );
                    } else {
                        $this->_helper->redirector ( 'index', 'test' );
                    }
                } else {
                    $testId = $this->getRequest()->getParam('testId');
                    if ($testId != '')
                    {
                        // выбираем из базы данные о редактируемом тесте
                        $objTests = new Tests ( );
                        $objTest = $objTests->getTestById( $testId );

                        if ($objTest) {
                            $arrQuestion = $objTests ->
                                getQuestionListByTestId( $testId );

                            $this -> view -> arrQuestion = $arrQuestion;
//                            $this -> view -> testId = $testId;
                        }
                    }
                }
            } else {
                $testId = $this->getRequest()->getParam('testId');
                if ($testId != '')
                {
                    // выбираем из базы данные о редактируемом тесте
                    $objTests = new Tests ( );
                    $objTest = $objTests->getTestById( $testId );

                    if ($objTest) {
                        $arrQuestion = $objTests ->
                            getQuestionListByTestId( $testId );

                        $this -> view -> arrQuestion = $arrQuestion;
                        $this -> view -> testId = $testId;
                        $objForm -> populate(
                            array( 'testName'           => $objTest -> t_name,
                                   'categoryId'         => $objTest -> cat_id,
                                   'testQuestionAmount' => sizeof( $arrQuestion ),
                                    'testId'            => $objTest -> t_id));
                    }
                }
            }
            $this -> view -> objForm = $objForm;
        }
    }

    /**
     * Удаление  теста
     * @return void
     */
    public function removeAction()
    {
        if ( $this -> _authorize( 'test', 'remove')) {
            $objTests = new Tests ();

            $arrParams = $this -> getRequest() -> getParams();

            if (array_key_exists( 'testId', $arrParams ) &&
                    !empty( $arrParams['testId'] ) ) {
                $objTests -> removeTestById( ( int ) $arrParams['testId'] );
            }

            $this->_forward ( 'index', 'test' );
        }
    }
}