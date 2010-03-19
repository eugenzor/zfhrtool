<?php
/**
 * @package zfhrtool
 * @subpackage Controller
 */


/**
 *  онтроллер тестов
 *
 * ќбеспечивает работу с тестами
 * @package zfhrtool
 * @subpackage Controller
 */

class TestController extends Controller_Action_Abstract
{

    /**
     * »нициализаци€ контроллера
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
//        Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
    }

    public function indexAction()
    {
        $arrParams = $this->getRequest()->getParams();
        if ( array_key_exists( "categoryId", $arrParams ) ) {
            $categoryId = ( int ) $arrParams["categoryId"];
        } else {
            $categoryId = -1;
        }

        if ( array_key_exists( "strTestFilter", $arrParams ) &&
                !empty( $arrParams["strTestFilter"] ) ) {
            $strTestFilter = strip_tags( trim( $arrParams["strTestFilter"] ) );
        } else {
            $strTestFilter = null;
        }

        $smarty = Zend_Registry::get('smarty');

        $objCategories = new Categories ();
        $arrCategory = $objCategories -> getCategoryList();

        $objTests = new Tests();
        $arrTest = $objTests ->
            getTestListByCategoryId($categoryId, $strTestFilter);

        $smarty -> assign('categoryId', $categoryId);
        $smarty -> assign('arrCategory', $arrCategory);
        $smarty -> assign('arrTest', $arrTest);
        $smarty -> display('test_cat.tpl');
    }

    public function editAction()
    {
        $smarty = Zend_Registry::get('smarty');
        $testId = $this->getRequest()->getParam('testId');
        if ($testId != '')
        {
            // выбираем из базы данные о редактируемом тесте
            $objTests = new Tests ( );
            $objTest = $objTests->getTestById( $testId );

            if ($objTest) {
                $arrQuestion = $objTests -> getQuestionListByTestId( $testId );

                $smarty -> assign( 'objTest', $objTest);
                $smarty -> assign( 'arrQuestion', $arrQuestion);
                $smarty -> assign( 'intQuestionAmount',
                    sizeof( $arrQuestion ) );
            }


        }
        $objCategories = new Categories ();
        $arrCategory = $objCategories -> getCategoryList();

        $smarty -> assign('arrCategory', $arrCategory);
        $smarty -> display('test_edit.tpl');
    }

    public function updateAction()
    {
        $objTests = new Tests ();

        $arrParams = $this->getRequest()->getParams();
        try {
            if (array_key_exists('testId', $arrParams) &&
                    !empty($arrParams['testId'])) {
                $testId = ( int ) $arrParams['testId'];
                $objTest = $objTests -> getTestById( $testId );
            } else {
                $testId = null;
                $objTest = $objTests -> createRow();
            }

            if (array_key_exists('testName', $arrParams)  &&
                    !empty($arrParams['testName'])) {
                $objTest->setName (
                    strip_tags( trim( $arrParams['testName'] ) ) );
            } else {
                throw new Exception ( '[LS_REQUIRED_PARAM_FAILED]' );
            }
            $objTest->setCategoryId (
                ( int ) $arrParams['categoryId'] );
            $objTest->setQuestionAmount (
                ( int ) $arrParams['testQuestionAmount'] );
            $objTest -> save();

        } catch ( Exception $e ){ print $e -> getMessage(); }

        if ( 'questionAdd' == $arrParams[ 'formAction' ] ) {
            if (!$testId) {
                $testId = $objTests -> getAdapter()-> lastInsertId();
            }

            $this->_helper -> redirector ( 'edit', 'question', null,
                array( 'testId' => $testId ) );
        } else {
            $this->_helper->redirector ( 'index', 'test' );
        }
    }

    public function removeAction()
    {
        $objTests = new Tests ();

        $arrParams = $this->getRequest()->getParams();

        try {
        if (array_key_exists('testId', $arrParams) &&
                !empty($arrParams['testId'])) {
            $objTests -> removeTestById( ( int ) $arrParams['testId']);
        }
        } catch ( Exception $e ){ print $e -> getMessage(); }

        $this->_helper->redirector ( 'index', 'test' );
    }
}