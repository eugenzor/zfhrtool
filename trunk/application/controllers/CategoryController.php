<?php
/**
 * @package zfhrtool
 * @subpackage Controller
 */


/**
 *  онтроллер категорий
 *
 * ќбеспечивает работу с категори€ми тестов
 * @package zfhrtool
 * @subpackage Controller
 */

class CategoryController extends Controller_Action_Abstract
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
        $smarty = Zend_Registry::get('smarty');

        $objCategories = new Categories ();
        $arrCategory = $objCategories -> getCategoryList(); 

        $smarty -> assign('arrCategory', $arrCategory);
        $smarty -> display('category_cat.tpl');
    }

    public function editAction()
    {
        $smarty = Zend_Registry::get('smarty');
        $categoryId = $this->getRequest()->getParam('categoryId');
        if ($categoryId != '')
        {
            // выбираем из базы данные о редактируемой категории
            $categories = new Categories ( );
            $objCategory = $categories->getCategoryById( $categoryId );

            if ($objCategory) {
                $smarty -> assign('objCategory', $objCategory);
            }

        }
        $smarty -> display('category_edit.tpl');
    }

    public function updateAction()
    {
        $objCategories = new Categories ();

        $arrParams = $this->getRequest()->getParams();
        try {
            if (array_key_exists('categoryId', $arrParams) &&
                    !empty($arrParams['categoryId'])) {
                $objCategory = $objCategories ->
                getCategoryById( ( int ) $arrParams['categoryId'] );
            } else {
                $objCategory = $objCategories -> createRow();
            }

            if (array_key_exists('categoryName', $arrParams)  &&
                    !empty($arrParams['categoryName'])) {
                $objCategory->setName (
                    strip_tags( trim( $arrParams['categoryName'] ) ) );
            } else {
                throw new Exception ( '[LS_REQUIRED_PARAM_FAILED]' );
            }
            $objCategory->setDescription (
                strip_tags( trim( $arrParams['categoryDescr'] ) ) );
//            print_r($objCategory);

            $objCategory -> save();
        } catch ( Exception $e ){ print $e -> getMessage(); }

        $this->_helper->redirector ( 'index', 'category' );
    }

    public function removeAction()
    {
        $objCategories = new Categories ();

        $arrParams = $this->getRequest()->getParams();

        try {
            if (array_key_exists('categoryId', $arrParams) &&
                    !empty($arrParams['categoryId'])) {
                $objCategories -> removeCategoryById($arrParams['categoryId']);
            }
        } catch ( Exception $e ){ print $e -> getMessage(); }

        $this->_helper->redirector ( 'index', 'category' );
    }
}