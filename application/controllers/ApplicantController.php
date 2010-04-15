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
            $arrVacancies = $objVacancies -> getVacancies();
            $objFilterForm -> setVacancies( $arrVacancies );

            if ($this->getRequest()->isPost()) {
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

            $this -> view -> orderBy = $this -> getRequest() -> getParam('orderBy');
            $this -> view -> arrApplicants = $arrApplicants;
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
            $objVacancies = new Vacancies ();
            $arrVacancy = $objVacancies -> getVacancies();
            $objForm -> setSelectOptions( $arrVacancy );

            if ($this->getRequest ()->isPost ()) {
                if ( $objForm->isValid ( $_POST )) {
                    // Выполняем update (insert/update данных о категории)
                    $objApplicants = new Applicants ();

                    $applicantId = $objForm -> applicantId -> getValue();
                    if ( !empty($applicantId)) {
                        $objApplicant = $objApplicants -> getApplicantById( $applicantId );
                    } else {
                        $applicantId = null;
                        $objForm -> Email -> addValidator('Db_NoRecordExists', true, array('applicants', 'email'));
                        $objApplicant = $objApplicants -> createRow();
                        $objApplicant->setStatus("new");
                    }

                    $LastName = $objForm -> LastName -> getValue();
                    $Name = $objForm -> Name -> getValue();
                    $Patronymic = $objForm -> Patronymic -> getValue();
                    $Birth = $objForm -> Birth -> getValue();
                    $VacancyId = $objForm -> VacancyId -> getValue();
                    $Email = $objForm -> Email -> getValue();
                    $Phone = $objForm -> Phone -> getValue();
                    $Resume = $objForm -> Resume -> getValue();

                    $objApplicant->setLastName ( $LastName );
                    $objApplicant->setName ( $Name );
                    $objApplicant->setPatronymic ( $Patronymic );
                    $objApplicant->setBirth ( $Birth );
                    $objApplicant->setVacancyId ( $VacancyId );
                    $objApplicant->setEmail ( $Email );
                    $objApplicant->setPhone ( $Phone );
                    $objApplicant->setResume ( $Resume );
                    if ($objApplicant->getStatus() == "staff")
                        $objApplicant->setNumber( $this -> getRequest() -> getParam('Number') );
                    $objApplicant -> save();
                    
                    if ($applicantId == null) {
                        $applicantId = $objApplicants -> getAdapter() -> lastInsertId();
                        $comments = new Comments();
                        $comment = $comments -> createRow();
                        $comment -> setUserId( Auth::getInstance() -> getIdentity() );
                        $comment -> setApplicantId($applicantId);
                        $comment -> setMessage("Applicant added to base");
                        $comment->save();
                    }
                    if ($objForm -> Photo -> getValue() != "") {
                        if ($objForm -> Photo -> receive()) {
                            rename( $objForm -> Photo -> getFileName(),
                                $_SERVER['DOCUMENT_ROOT'] .
                                '/public/images/photos/' .
                                $applicantId . '.jpg'
                            );
                        }
                    }

                    $this->_helper->redirector ( 'index', 'applicant' );
                } else {
                    $applicantId = $this->getRequest()->getParam('applicantId');
                    if ($applicantId != '')
                    {
                        // выбираем из базы данные о редактируемом соискателе
                        $objApplicants = new Applicants ( );
                        $objApplicant = $objApplicants->getApplicantById( $applicantId );

                        if ($objApplicant) {
                            $this -> view -> objApplicant = $objApplicant;
                            $objForm -> populate(
                                array( 'LastName'   =>  $objForm -> LastName,
                                       'Name'       =>  $objForm -> Name,
                                       'Patronymic' =>  $objForm -> Patronymic,
                                       'Birth'      =>  $objForm -> Birth,
                                       'VacancyId'  =>  $objForm -> VacancyId,
                                       'Email'      =>  $objForm -> Email,  
                                       'Phone'      =>  $objForm -> Phone,  
                                       'Resume'     =>  $objForm -> Resume,
                                       'Number'     =>  $objForm -> Number,
                                       'applicantId'=>  $applicantId
                                )
                            );
                        }
                    }
                }
            } else {
                $applicantId = $this->getRequest()->getParam('applicantId');
                if ($applicantId != '')
                {
                    // выбираем из базы данные о редактируемом соискателе
                    $objApplicants = new Applicants ( );
                    $objApplicant = $objApplicants->getApplicantById( $applicantId );
                    if ($objApplicant) {
                        $this -> view -> applicantId = $applicantId;
                        if ($objApplicant -> getStatus() == "staff")
                            $objForm -> showNumber();
                        $objForm -> populate(
                            array(
				'LastName'   =>  $objApplicant -> last_name,
				'Name'       =>  $objApplicant -> name,
				'Patronymic' =>  $objApplicant -> patronymic,
				'Birth'      =>  substr($objApplicant -> birth, 0 , 10),
				'VacancyId'  =>  $objApplicant -> v_id,
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

            if (array_key_exists( 'applicantId', $arrParams ) &&
                    !empty( $arrParams['applicantId'] ) ) {
                $objApplicants -> removeApplicantById( $arrParams['applicantId'] );
            }
            // @todo Удаление комментариев
            $this->_forward ( 'index', 'applicant' );
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
                $objApplicants = new Applicants ( );
                $objApplicant = $objApplicants->getApplicantById( $applicantId );
                if ($objApplicant) {
                    $this -> view -> applicant = $objApplicant;
                    $comments = new Comments();
                    if ( $this -> isAllowed( 'comments', 'add')) {
                        $form = new Form_Applicant_Comment();
                        $this -> view -> commentForm = $form;
                        if ($this->getRequest () -> isPost ()) {
                            if ( $form -> isValid ( $_POST )) {
                                $comment = $comments -> createRow();
                                $comment -> setUserId( Auth::getInstance() -> getIdentity() );
                                $comment -> setApplicantId($applicantId);
                                $comment -> setMessage( $form -> Comment -> getValue() );
                                $comment -> save();
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
                    $objApplicant = $objApplicants->getApplicantById( $form -> applicantId -> getValue());
                    if ($objApplicant
                        && $form -> Status -> getValue() != $objApplicant -> getStatus()) {
                        $status = $objApplicant -> getStatus();
                        $objApplicant -> setStatus( $form -> Status -> getValue() );
                        $objApplicant -> save();

                        $comments = new Comments();
                        $comment = $comments -> createRow();
                        $comment -> setUserId( Auth::getInstance() -> getIdentity() );
                        $comment -> setApplicantId( $objApplicant -> id );
                        $comment -> setMessage(
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
                            . $form -> Comment -> getValue()
                        );
                        $comment->save();
                        $this -> _helper -> redirector ( 'index', 'applicant' );
                    }
                }
            }

                // выбираем из базы данные о соискателе
                $objApplicant = $objApplicants->getApplicantById( $applicantId );
                if ($objApplicant) {
                    $this -> view -> applicant = $objApplicant;
                    $form -> populate(
                        array(
                            'Status' =>  $objApplicant -> getStatus(),
                            'applicantId' =>  $objApplicant -> id
                        )
                    );
                }
                $this -> view -> form = $form;
        }
    }    

}