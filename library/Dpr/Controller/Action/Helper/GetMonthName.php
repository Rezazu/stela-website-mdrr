<?php
class Dpr_Controller_Action_Helper_GetMonthName extends Zend_Controller_Action_Helper_Abstract
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
		switch($expression)
		{
			case 1:
				$bulan = "Januari"; break;
			case 2:
				$bulan = "Februari"; break;
			case 3:
				$bulan = "Maret"; break;
			case 4:
				$bulan = "April"; break;
			case 5:
				$bulan = "Mei"; break;
			case 6:
				$bulan = "Juni"; break;
			case 7:
				$bulan = "Juli"; break;
			case 8:
				$bulan = "Agustus"; break;
			case 9:
				$bulan = "September"; break;
			case 10:
				$bulan = "Oktober"; break;
			case 11:
				$bulan = "November"; break;
			case 12:
				$bulan = "Desember"; break;
		}
        return $bulan;
    }

}