<?php
class Dpr_Helper_BaseUrl extends Zend_Controller_Action_Helper_Abstract
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
	public function direct($url)
    {		
		return 'C:/www/persuratan/public/' . $url;
    }

}