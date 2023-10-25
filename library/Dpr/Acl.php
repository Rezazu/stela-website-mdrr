<?php
class Dpr_Acl extends Zend_Acl
{

	public function __construct()
	{
		$this->addRoles();
	}

	protected function addRoles()
	{
		$this->addRole(new Zend_Acl_role('admin'));
		$this->addRole(new Zend_Acl_role('verificator'));
		$this->addRole(new Zend_Acl_role('programmer'));
		$this->addRole(new Zend_Acl_role('user'));
		$this->addRole(new Zend_Acl_role('guest'));
		$this->addRole(new Zend_Acl_role('viewer'));
		$this->addRole(new Zend_Acl_role('helpdesk'));
		$this->addRole(new Zend_Acl_role('servicedesk'));
		$this->addRole(new Zend_Acl_role('IT specialist'));
	}
}
