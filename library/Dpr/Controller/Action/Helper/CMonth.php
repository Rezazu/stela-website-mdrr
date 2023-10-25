<?php
class Dpr_Controller_Action_Helper_CMonth extends Zend_Controller_Action_Helper_Abstract
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
			switch($expression){
				case '01' : $bulan = 'I';	break;
				case '02' : $bulan = 'II';	break;
				case '03' : $bulan = 'III';	break;
				case '04' : $bulan = 'IV';	break;
				case '05' : $bulan = 'V';	break;
				case '06' : $bulan = 'VI';	break;
				case '07' : $bulan = 'VII';	break;
				case '08' : $bulan = 'VIII';break;
				case '09' : $bulan = 'IX';	break;
				case '10' : $bulan = 'X';	break;
				case '11' : $bulan = 'XI';	break;
				case '12' : $bulan = 'XII';	break;
			}
			return $bulan;
		}
    }

}