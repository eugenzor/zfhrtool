<?php
/**
 * @package zfhrtool
 * @subpackage Controller
 */


/**
 * Контроллер категорий
 *
 * Обеспечивает работу с категориями тестов
 * @package zfhrtool
 * @subpackage Controller
 */

class CategoryController extends Controller_Action_Abstract
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

        if ( $this -> _authorize( 'category', 'view')) {
            $objCategories = new Categories ();
            $arrCategory = $objCategories -> getCategoryList();

            $this -> view -> arrCategory = $arrCategory;
        }
    }

    /**
     * Добавление/обновление категории
     * @return void
     */
    public function editAction()
    {
        if ( $this -> _authorize( 'category', 'edit')) {
            $form = new Form_Category_Edit();
            if ($this->getRequest ()->isPost ()){
                if ( $form->isValid ( $_POST )) {
                    // Выполняем update (insert/update данных о категории)
                    $objCategories = new Categories ();

                    $categoryId = $form -> categoryId -> getValue();
                    if ( !empty($categoryId)) {
                            $objCategory = $objCategories ->
                            getCategoryById( $categoryId );
                        } else {
                            $objCategory = $objCategories -> createRow();
                        }


                    $categoryName = $form -> categoryName -> getValue();
                    // trim и htmlEnteties делают фильтры zend_form
                    $objCategory -> setName ( $categoryName );
                    $categoryDescr = $form -> categoryDescr -> getValue();
                    $objCategory -> setDescription ( $categoryDescr );
                    $objCategory -> save();

                    $this->_forward( 'index', 'category' );
                }
            } else {
                $categoryId = ( int ) $this->getRequest()->getParam('categoryId');
                if ($categoryId != '')
                {
                    // выбираем из базы данные о редактируемой категории
                    $categories = new Categories ( );
                    $objCategory = $categories->getCategoryById( $categoryId );

                    if ($objCategory) {
                        $this -> view -> objCategory = $objCategory;
                        $form -> populate(
                            array( 'categoryName'   =>  $objCategory -> cat_name,
                                   'categoryDescr'  =>  $objCategory -> cat_descr,
                                   'categoryId'     =>  $objCategory -> cat_id) );
                    }
                }
            }
    //        print_r( $form->getErrors());
            //  @todo: НЕ выводит сообщения об ошибках в форму
            $this -> view -> objCategoryEditForm = $form;
        }
    }

    /**
     * Удаление категории
     * @return void
     */
    public function removeAction()
    {
        if ( $this -> _authorize( 'category', 'edit')) {
            $objCategories = new Categories ();

            $arrParams = $this->getRequest()->getParams();

            if (array_key_exists('categoryId', $arrParams) &&
                    !empty($arrParams['categoryId'])) {
                $objCategories -> removeCategoryById($arrParams['categoryId']);
            }

            $this->_forward ( 'index', 'category' );
        }
    }
}