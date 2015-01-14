<?php
header('Content-Type: text/xml; charset=utf-8');
require_once 'config/class.ServerConfig.php';

define('ROOT_DIR',    ServerConfig::getProjectRoot());
define('CORE_DIR',    ROOT_DIR . DIRECTORY_SEPARATOR . 'core');
define('WEB_DIR',     ROOT_DIR . DIRECTORY_SEPARATOR . 'web');
define('CONFIG_DIR',  ROOT_DIR . DIRECTORY_SEPARATOR . 'config');
define('INIT_DIR',    CORE_DIR . DIRECTORY_SEPARATOR . 'init');
define('MODULES_DIR', CORE_DIR . DIRECTORY_SEPARATOR . 'modules');

$config = Array();
require_once INIT_DIR . DIRECTORY_SEPARATOR . 'init.syslog.php';
//require_once CONFIG_DIR . DIRECTORY_SEPARATOR . ServerConfig::getBranch() . DIRECTORY_SEPARATOR . 'main.php';
require_once 'Zend/Http/Client.php';
require_once 'protocols/payments-kernel/init.php';
require_once INIT_DIR . DIRECTORY_SEPARATOR . 'init.proxygate.php';

global $config;

// конфиг шлюзов
$config['urlGate'] = Array();
$config['urlGate']['rapida'] = 'rapida';
$config['urlGate']['creditpilot'] = 'creditpilot';
$config['urlGate']['airnet'] = 'airnet';
$config['urlGate']['contact-ng'] = 'contact-ng';
$config['urlGate']['mobilecard'] = 'mobilecard';
$config['urlGate']['rapida'] = 'rapida';
$config['urlGate']['osmp'] = 'osmp';
$config['urlGate']['airlan'] = 'airlan';


// конфиг терминалов
$config['termId'] = Array();
$config['termId']['MCTER01'] = '78-2-1';
$config['termId']['MCTER02'] = '78-2-2';
$config['termId']['MCTER03'] = '78-8-1';
$config['termId']['MCTER04'] = '78-8-2';
$config['termId']['MCTER05'] = '78-6-1';
$config['termId']['MCTER06'] = '78-7-1';
$config['termId']['MCTER07'] = '78-1-2';
$config['termId']['MCTER08'] = '78-5-1';
$config['termId']['MCTER09'] = '78-9-1';
$config['termId']['MCTER10'] = '78-10-1';
$config['termId']['MCTER11'] = '78-11-1';
$config['termId']['MCTER12'] = '78-12-1';





