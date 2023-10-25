<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	private $_acl = null;
	private $_auth = null;

	protected function _initAutoload()
	{
		$modelLoader = Zend_Loader_Autoloader::getInstance();
		$modelLoader->registerNamespace('Dpr_');
		//$loader->registerNamespace('PEAR_');
		$modelLoader->setFallbackAutoloader(auto);

		//$this->_acl = new Model_LibraryAcl;
		$this->_auth = Zend_Auth::getInstance();

		$fc = Zend_Controller_Front::getInstance();
		$fc->throwExceptions(true);
		//$fc->registerPlugin(new Plugin_AccessCheck($this->_acl));

		return $modelLoader;
	}

	/*protected function _initDb()
    {
		if ($this->hasPluginResource('db')) {
			$resource = $this->getPluginResource('db');
			$db = $resource->init();
			return $db;
		}
	}*/

	protected function _initRoutesAndControllers()
	{
		$frontController = Zend_Controller_Front::getInstance();
		//$frontController->setBaseUrl(BASE_URL);
		//$frontController->setBaseUrl();
	}

	function _initViewHelpers()
	{
		Zend_Layout::startMvc();

		$view = Zend_Layout::getMvcInstance()->getView();
		$view->addHelperPath('../library/Dpr/View/Helper', 'Dpr_View_Helper');
		$view->doctype('XHTML1_STRICT');

		$acl = new Dpr_Acl();
		$aclHelper = new Dpr_Controller_Action_Helper_Acl(null, array('acl' => $acl));
		Zend_Controller_Action_HelperBroker::addHelper($aclHelper);
		//Zend_Controller_Action_HelperBroker::addPath('../library/Dpr/Controller/Helper');
		Zend_Controller_Action_HelperBroker::addPrefix('Dpr_Helper');
		Zend_Controller_Action_HelperBroker::addPath('../library/Dpr/Controller/Action/Helper', 'Dpr_Controller_Action_Helper');
	}

	public function _initDbRegistry()
	{
		$this->bootstrap('multidb');
		$multidb = $this->getPluginResource('multidb');
		Zend_Registry::set('db_stela', $multidb->getDb('db_stela'));
	}
}
