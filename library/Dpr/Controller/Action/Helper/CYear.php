<?php
class Dpr_Controller_Action_Helper_CYear extends Zend_Controller_Action_Helper_Abstract
{
	
	/**
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;

    /**
     * Constructor: initialize plugin loader
     *
     * @return void
     */
    public function __construct()
    {
        $this->pluginLoader = new Zend_Loader_PluginLoader();
    }

	//function serverProcessing()
	public function direct($expression)
    {		
		if ($expression == "") 
		{
			return null;
		} else {
			list($tgl, $bln, $thn) = explode('-', $expression);
			return ($thn);
		}
    }

}