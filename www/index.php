<?php
ob_start('ob_gzhandler');

// ======================
// = defining constants =
// ======================
define('DIR_WEB', dirname(__FILE__));
define('DIR_PHPLIB', dirname(__FILE__).'/lib/php');
define('DIR_SYS', DIR_PHPLIB.'/system');
define('DIR_CTRL', DIR_PHPLIB.'/controller');
define('DIR_TMPL', DIR_PHPLIB.'/template');
define('DIR_VIEW', DIR_PHPLIB.'/view');
define('DIR_PLUGINS', DIR_PHPLIB.'/plugins');

// =====================
// = disecting the URI =
// =====================
$ru = &$_SERVER['REQUEST_URI'];
$qmp = strpos($ru, '?');
list($path, $params) = $qmp === FALSE
    ? array($ru, NULL)
    : array(substr($ru, 0, $qmp), substr($ru, $qmp + 1));
$parts = explode('/', $path);
$i = 0;
foreach ($parts as $part)
{
    if (strlen($part) && $part !== '..' && $part !== '.')
    {
        define('URI_PART_'.$i++, $part);
    }
}
define('URI_PARAM', isset($params) ? '' : $params);
define('URI_PARTS', $i);
define('URI_PATH', $path);
define('URI_REQUEST', $_SERVER['REQUEST_URI']);

// ==========================
// = routing and other init =
// ========================== 
session_start();
require_once DIR_SYS.'/Config.php';
include DIR_SYS.'/router.php';
include DIR_SYS.'/config.routes.php';

$settings = Config::getInstance();

if ($ctrl = Router::controller()) 
{
    include $ctrl;
}
else 
{
    header('HTTP/1.1 404 Not Found');
}

?>
