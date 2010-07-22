<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Дополнение к стандартному контроллеру
 * @package zfhrtool
 * @subpackage Model
 */
abstract class Controller_Action_Abstract extends Zend_Controller_Action
{
    protected $_baseUrl;
    protected $_navigation;

    /**
     * Инициализация базовых настроек
     * @return void
     */
    public function init() {
        parent::init ();
        $this->_baseUrl = $this->getFrontController ()->getBaseUrl ();

        $auth = Auth::getInstance();
        $acl = new Acl();
        $auth->setAcl($acl);

        $this->view->doctype ( 'XHTML1_TRANSITIONAL' );
        $this->view->headTitle ()->setSeparator ( ' :: ' );
        $this->view->headTitle('HR');
        $this->view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
        $this->view->addHelperPath('../application/views/helpers/', 'Helper');

        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole('guest');

        $this->_setNavigation();
    }

    /**
     * Обработка в случае потери авторизации
     * @return bool
     */
    protected function _accesFail()
    {
        $url = new Zend_Session_Namespace ( 'Authentication' );

        $url->backUrl = $this->getFrontController()->getRequest()->getPathInfo();
        $this->_redirect ( '/user/signin' );

        $this->_helper->removeHelper('viewRenderer');
        $this->_helper->layout->disableLayout();
        return false;
    }

    /**
     * Проверка наличие прав доступа
     * @param string $resource запрашиваемый ресурс
     * @param string|null $privilege запрашиваемая привилегия
     * @return bool
     */
    protected function _authorize($resource, $privilege = null) {
        $auth = Auth::getInstance();

        if ($auth->hasIdentity () === false) {
            return $this->_accesFail();
        }

        if ($auth->isAllowed ( $resource, $privilege )) {
            return true;
        }
        return $this->_accesFail();
    }

    /**
     * Устанавливает меню навигации
     * @return Controller_Action_Abstract
     */
    protected function _setNavigation()
    {
        $this->_navigation = new Navigation();
        return $this;
    }

//    protected function _addToNavigation($page, $findedKey = NULL, $findedValue = NULL)
//    {
//        if (empty($findedKey) && empty($findedValue)){
//            $findedKey = 'controller';
//            $findedValue = $this->_request->getControllerName();
//        }
//
//        if (empty($page['controller'])){
//            $page['controller'] = $this->_request->getControllerName();
//        }
//
//        if (empty($page['action'])){
//            $page['action'] = $this->_request->getActionName();
//        }
//
//        if (empty($page['params'])){
//            $page['params'] = $this->_request->getParams();
//        }
//        $page = $this->_navigation->findOneBy($findedKey, $findedValue);
//        $page->set('pages', array($page));
//    }
//
//    protected function _setActiveNavigation($key = NULL, $value = NULL)
//    {
//        if (empty($key) && empty($value)){
//            $key = 'controller';
//            $value = $this->_request->getControllerName();
//        }
//        $this->_navigation->findOneBy($key, $value)->setActive();
//    }

    /**
     * Работы данного слоя перед выводом вида
     * @return void
     */
    public function preDispatch()
    {
        $auth = Auth::getInstance ();
        $this->view->navigation($this->_navigation)
            ->setAcl($auth->getAcl())
            ->setRole($auth->getUser()->getRole());
        parent::preDispatch();
    }

    /**
     * Проверка на наличие прав доступа
     * @param string $resource запрашиваемый ресурс
     * @param string|null $privilege запрашиваемая привилегия
     * @return bool
     */
    protected function isAllowed($resource, $privilege = null) {
        $auth = Auth::getInstance();
        return $auth->isAllowed ( $resource, $privilege );
    }
}
