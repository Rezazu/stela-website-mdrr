<?php

// timezone for an Asia in Jakarta
date_default_timezone_set('Asia/Jakarta');

defined('BASE_URL')
|| define('BASE_URL', '/tes/public/');

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../library/phpexcel'),
    realpath(APPLICATION_PATH . '/../application/models'),
    realpath(APPLICATION_PATH . '/../application/classes'),
    get_include_path(),
)));

/*
    realpath(APPLICATION_PATH . '/forms'),
    realpath(APPLICATION_PATH . '/plugins'),
	*/

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

try {
    $application->bootstrap()
        ->run();
} catch (Exception $exception) {
    if (explode(' (', $exception->getMessage())[0] == 'Invalid controller specified'){
        include_once APPLICATION_PATH . '/views/scripts/error/404.phtml';
    }else{
        var_dump($exception);
    }
}