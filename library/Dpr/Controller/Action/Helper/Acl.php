<?php
class Dpr_Controller_Action_Helper_Acl extends Zend_Controller_Action_Helper_Abstract
{

	protected $_action;
	protected $_auth;
	protected $_acl;
	protected $_controllerName;
	protected $_notallowed;

	public function __construct(Zend_View_Interface $view = null, array $options = array())
	{
		$this->_auth = Zend_Auth::getInstance();
		$this->_acl = $options['acl'];
	}

	public function init()
	{
		$this->_action = $this->getActionController();
		$controller = $this->_action->getRequest()->getControllerName();
		if (!$this->_acl->has($controller)) {
			$this->_acl->add(new Zend_Acl_Resource($controller));
		}
	}

	public function preDispatch()
	{
		if ($this->_auth->hasIdentity()) {
			$user = $this->_auth->getIdentity();
			if (is_object($user)) {
				$role = $this->_auth->getIdentity()->peran_aktif;
			}
		}
		$request = $this->_action->getRequest();
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$module = $request->getModuleName();
		$this->_controllerName = $controller;
		$resource = $controller;
		$privilege = $action;
		if (!$this->_acl->has($resource)) {
			$resource = null;
		}
		// check roles
		if (!$this->_acl->isAllowed($role, $resource, $privilege)) {
			$this->_notallowed = true;
			$this->getResponse()->setRedirect('/login', 301);
		} else {
			$this->_notallowed = false;
		}
	}

	public function notallowed()
	{
		return $this->_notallowed;
	}

	public function allow($roles = null, $actions = null)
	{
		$resource = $this->_controllerName;
		$this->_acl->allow($roles, $resource, $actions);
		return $this;
	}

	public function deny($roles = null, $actions = null)
	{
		$resource = $this->_controllerName;
		$this->_acl->deny($roles, $resource, $actions);
		return $this;
	}

	// need auth if not authenticated user redirect to login
	/**
	 * controller need authenticated user,if no redirect to login
	 */
	public function needAuth()
	{
		if (!$this->_auth->hasIdentity()) {
			$this->getResponse()->setRedirect('/login', 301);
		}
	}

	// redirect if authenticated user
	/**
	 * @param string $url url to redirect if authenticated user
	 */
	public function redirectIfAuthenticated($url = '/')
	{
		if ($this->_auth->hasIdentity()) {
			$this->getResponse()->setRedirect($url);
		}
	}
}
