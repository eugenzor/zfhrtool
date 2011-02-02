<?php
/**
 * @package zfhrtool
 * @subpackage Controller
 */


/**
 * Контроллер категорий вопросов
 *
 * Обеспечивает работу с категориями вопросов теста
 * @package zfhrtool
 * @subpackage Controller
 */

class QuestionCategoryController extends Controller_Action_Abstract
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
     * Список категорий (главная страница)
     * @return void
     */
    public function indexAction()
    {
        if ( $this -> _authorize( 'test', 'view')) {
            $testId = $this->getRequest()->getParam('testId');
            $objCategories = new QuestionCategories ();
            $arrCategory = $objCategories -> getCategoryListByTestId($testId);
            $this -> view -> arrCategory = $arrCategory;
            $this -> view -> testId = $testId;
       }
    }

    /**
     * Добавление/обновление категории
     * @return void
     */
    public function editAction()
    {
        if ( $this -> _authorize( 'test', 'edit')) {
            $form = new Form_QuestionCategory_Edit();
            if ($this->getRequest ()->isPost ()){
                if ( $form->isValid ( $_POST )) {
                    // Выполняем update (insert/update данных о категории)
                    $objCategories = new QuestionCategories ();

                    $categoryId = $form -> categoryId -> getValue();
                    if ( !empty($categoryId)) {
                            $objCategory = $objCategories -> find( $categoryId );
                            $objCategory = $objCategory -> current();
                        } else {
                            $objCategory = $objCategories -> createRow();
                        }


                    $categoryName = $form -> categoryName -> getValue();
                    $objCategory -> setName ( $categoryName );
                    $categoryDescr = $form -> categoryDescr -> getValue();
                    $objCategory -> setDescription ( $categoryDescr );
                    $categoryTestId = $form -> testId -> getValue();
                    $objCategory -> setTestId ( $categoryTestId );
                    $objCategory -> save();
                    $reqParams['testId'] = $categoryTestId;

                    $this->_helper -> redirector ( 'index', 'questioncategory', null, $reqParams );
                } else {
                    $this -> view -> testId = $form -> testId -> getValue();
                }
            } else {
                $categoryId = ( int ) $this->getRequest()->getParam('categoryId');
                $testId = ( int ) $this->getRequest()->getParam('testId');
                $this -> view -> testId = $testId;
                $form -> populate( array( 'testId' => $testId ) );
                if ($categoryId != '')
                {
                    // выбираем из базы данные о редактируемой категории
                    $categories = new QuestionCategories();
                    $objCategory = $categories -> find($categoryId) -> current();
                    if ($objCategory) {
                        $form -> populate(
                            array( 'categoryName'  =>  $objCategory -> getName(),
                                   'categoryDescr' =>  $objCategory -> getDescription(),
                                   'categoryId'    =>  $objCategory -> getId()));
                    }
                }
            }
            $this -> view -> objCategoryEditForm = $form;
        }
    }

    /**
     * Удаление категории
     * @return void
     */
    public function removeAction()
    {
        if ( $this -> _authorize( 'test', 'edit')) {
            $objCategories = new QuestionCategories ();

            $arrParams = $this->getRequest()->getParams();
            $reqParams['testId'] = $arrParams['testId'];
            

            if (array_key_exists('categoryId', $arrParams) &&
                    !empty($arrParams['categoryId'])) {
                $objCategories -> removeCategoryById($arrParams['categoryId']);
            }
            $this->_helper -> redirector ('index', 'questioncategory', null, $reqParams);
        }
    }
}