<?php
/**
 * @package zfhrtool
 * @subpackage Controller
 */


/**
 * Контроллер соискателей
 *
 * Обеспечивает работу с соискателями
 * @package zfhrtool
 * @subpackage Controller
 */
class ApplicantController extends Controller_Action_Abstract
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
     * Список соискателей (главная страница)
     * @return void
     */
    public function indexAction()
    {
        if ( $this -> _authorize( 'applicants', 'view')) {
            $objFilterForm = new Form_Applicant_Filter();
            $objVacancies = new Vacancies();
            $objFilterForm -> setVacancies(
                $objVacancies -> fetchAll() -> toArray()
            );

            if ($this -> getRequest() -> isPost()) {
                $arrParams = $this -> _request -> getPost();
                $vacancyId = ( int ) $arrParams['vacancyId'];
                $status = $arrParams['status'];
                $objFilterForm -> populate( $arrParams );
            } else {
                $vacancyId = -1;
                $status = -1;
            }

            $objApplicants = new Applicants();
            $arrApplicants = $objApplicants -> getApplicants(
                $vacancyId, $status,
                $this -> getRequest() -> getParam('orderBy')
            );

            $objVT = new VacanciesTest();

            $this -> view -> orderBy = $this -> getRequest() -> getParam('orderBy');
            $this -> view -> arrApplicants = $arrApplicants;
            $this -> view -> arrTests= $objVT -> getTestsA();
            $this -> view -> objFilterForm = $objFilterForm;
            
            $this -> view -> can_edit = $this -> isAllowed( 'applicants', 'edit' );
            $this -> view -> can_remove = $this -> isAllowed( 'applicants', 'remove' );
            $this -> view -> can_change_status = $this -> isAllowed( 'applicants', 'change_status' );
        }
    }

    /**
     * Добавление соискателя
     * @return void
     */
    public function addAction()
    {
        if ( $this -> _authorize( 'applicants', 'add') )
            $this -> edit('add');
    }

    /**
     * обновление соискателя
     * @return void
     */
    public function editAction()
    {
        if ( $this -> _authorize( 'applicants', 'edit') )
            $this -> edit('edit');
    }

    /**
     * Добавление/обновление соискателя
     * @return void
     */
    private function edit($action)
    {
        if ( ($action == 'edit' && $this -> isAllowed( 'applicants', 'edit'))
            || ($action == 'add' && $this -> isAllowed( 'applicants', 'add')) ) {
            $objForm = new Form_Applicant_Edit();
            $objForm -> setAction ( $this-> view -> url ( array (
                'controller' => 'applicant',
                'action' => $action
            ) ) );
            $objVacancies = new Vacancies();
            $objForm -> setSelectOptions(
                $objVacancies -> fetchAll() -> toArray()
            );

            if ( $this -> getRequest() -> isPost() ) {
                if ( $objForm -> isValid ( $_POST ) ) {                  
                    $objApplicants = new Applicants ();
                    $applicantId = $objForm -> applicantId -> getValue();
                    $objApplicant = $objApplicants -> getObjectById( $applicantId );
                    if (!($objApplicant instanceof Applicant)) {
                        $applicantId = null;
                        $objApplicant = $objApplicants -> createRow();
                        $objApplicant -> status = "new";
                    }

                    $LastName = $objForm -> LastName -> getValue();
                    $Name = $objForm -> Name -> getValue();
                    $Patronymic = $objForm -> Patronymic -> getValue();
                    $Birth = $objForm -> Birth -> getValue();
                    $VacancyId = $objForm -> VacancyId -> getValue();
                    $Email = $objForm -> Email -> getValue();
                    $Phone = $objForm -> Phone -> getValue();
                    $Resume = $objForm -> Resume -> getValue();

                    $objApplicant -> last_name = $LastName;
                    $objApplicant -> name = $Name;
                    $objApplicant -> patronymic = $Patronymic;
                    $objApplicant -> birth = $Birth;
                    $objApplicant -> vacancy_id = $VacancyId;
                    $objApplicant -> email = $Email;
                    $objApplicant -> phone = $Phone;
                    $objApplicant -> resume = $Resume;
                    if ($objApplicant -> status == "staff")
                        $objApplicant -> number = $this -> getRequest() -> getParam('Number');
                    $objApplicant -> save();
                    
                    if ($applicantId == null) {
                        $applicantId = $objApplicants -> getAdapter() -> lastInsertId();
                        $comments = new Comments();
                        $comment = $comments -> createRow();
                        $comment -> user_id = Auth::getInstance() -> getIdentity();
                        $comment -> applicant_id = $applicantId;
                        $comment -> comment = "Applicant added to base";
                        $comment -> save();
                    }
                    if ($objForm -> Photo -> getValue() != "") {
                        if ($objForm -> Photo -> receive()) {
                            $filename = $_SERVER['DOCUMENT_ROOT'] .
                                '/public/images/photos/' .
                                $applicantId . '.jpg';
                            @unlink( $filename );
                            rename(
                                $objForm -> Photo -> getFileName(),
                                $filename
                            );
                        }
                    }
                    $this -> _helper -> redirector ( 'index', 'applicant' );
                } 
                else
                    $objForm -> populate( $this-> getRequest() -> getParams() );
            } else {
                $applicantId = $this->getRequest()->getParam('applicantId');
                if ($applicantId != '')
                {
                    // выбираем из базы данные о редактируемом соискателе
                    $objApplicants = new Applicants ( );
                    $objApplicant = $objApplicants->getObjectById( $applicantId );
                    if ($objApplicant) {
                        $this -> view -> applicantId = $applicantId;
                        if ($objApplicant -> status == "staff")
                            $objForm -> showNumber();
                        $objForm -> populate(
                            array(
				'LastName'   =>  $objApplicant -> last_name,
				'Name'       =>  $objApplicant -> name,
				'Patronymic' =>  $objApplicant -> patronymic,
				'Birth'      =>  substr($objApplicant -> birth, 0 , 10),
				'VacancyId'  =>  $objApplicant -> vacancy_id,
				'Email'      =>  $objApplicant -> email,  
				'Phone'      =>  $objApplicant -> phone,  
				'Resume'     =>  $objApplicant -> resume,
				'Number'     =>  $objApplicant -> number,
                                'applicantId'=>  $applicantId
                            )
                        );
                    }
                }
            }
            $this -> view -> objForm = $objForm;
        }
    }

    /**
     * Удаление  соискателя
     * @return void
     */
    public function removeAction()
    {
        if ( $this -> _authorize( 'applicants', 'remove')) {
            $objApplicants = new Applicants ();
            $arrParams = $this -> getRequest() -> getParams();
            $applicant = $objApplicants -> getObjectById(
                $this -> getRequest() -> getParam('applicantId')
            );
            
            if ( !($applicant instanceof Applicant) )
                throw new Zend_Exception('Error while deleting applicant.');
            $applicant -> delete();
            $this -> _helper -> redirector ( 'index', 'applicant' );
        }
    }
    
    /**
     * Информация о соискателе
     * @return void
     */
    public function showAction()
    {
        if ( $this -> _authorize( 'applicants', 'view')) {
            $applicantId = $this->getRequest()->getParam('applicantId');
            if ($applicantId != '')
            {
                $objApplicants = new Applicants ();
                $objApplicant = $objApplicants->getObjectById( $applicantId );
                if ($objApplicant) {
                    $this -> view -> applicant = $objApplicant;
                    $comments = new Comments();
                    if ( $this -> isAllowed( 'comments', 'add')) {
                        $form = new Form_Applicant_Comment();
                        $this -> view -> commentForm = $form;
                        if ($this->getRequest () -> isPost ()) {
                            if ( $form -> isValid ( $_POST )) {
                                $comment = $comments -> createRow();
                                $comment -> user_id = Auth::getInstance() -> getIdentity();
                                $comment -> applicant_id = $applicantId;
                                $comment -> comment = $form -> Comment -> getValue();
                                $comment -> save();
                                $this -> _helper -> redirector('show', 'applicant', 'default',
                                    array( 'applicantId' => $applicantId)
                                );
                            }
                        }
                    }
                    if ( $this -> isAllowed( 'comments', 'view'))
                        $this -> view -> comments = $comments -> getComments($applicantId);
                }
            }
        }
    }    

    /**
     * Изменение статуса соискателя
     * @return void
     */
    public function statusAction()
    {
        if ( $this -> _authorize( 'applicants', 'status')) {
            $applicantId = $this->getRequest()->getParam('applicantId');
            $form = new Form_Applicant_Status();
            $objApplicants = new Applicants ( );

            if ($this -> getRequest () -> isPost ()) {
                if ( $form -> isValid ( $_POST )) {
                    $objApplicant = $objApplicants->getObjectById( $form -> applicantId -> getValue());
                    if ($objApplicant
                        && $form -> Status -> getValue() != $objApplicant -> status) {
                        $status = $objApplicant -> status;
                        $objApplicant -> status = $form -> Status -> getValue();
                        $objApplicant -> save();

                        $comments = new Comments();
                        $comment = $comments -> createRow();
                        $comment -> user_id = Auth::getInstance() -> getIdentity();
                        $comment -> applicant_id = $objApplicant -> id;
                        $comment -> comment =
                            'Applicant status changed from "'
                            . $this-> view -> translate(
                                '[LS_STATUS_'
                                . strtoupper($status)
                                . ']')
                            .'" to "'
                            . $this-> view -> translate(
                                '[LS_STATUS_'
                                . strtoupper($form -> Status -> getValue())
                                . ']')
                            . '"<br>'
                            . $form -> Comment -> getValue();
                        $comment->save();
                        $this -> _helper -> redirector ( 'index', 'applicant' );
                    }
                }
            } else {
                // выбираем из базы данные о соискателе
                
                $objApplicant = $objApplicants->getObjectById( $applicantId );
                if ($objApplicant) {
                    $form -> populate(
                        array(
                            'Status' =>  $objApplicant -> status,
                            'applicantId' =>  $objApplicant -> id
                        )
                    );
                }
            }
            $this -> view -> form = $form;
        }
    }    

}