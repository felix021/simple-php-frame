<?php
error_reporting(E_ALL | E_STRICT);

ob_start();
session_start();

define("ROOT", dirname(__FILE__));
require_once(ROOT . '/config/config.php');
require_once(ROOT . '/include/functions.php');
require_once(ROOT . '/include/logger.php');

date_default_timezone_set(config::TIMEZONE);

//清理多余的斜杠
if (get_magic_quotes_gpc()) {
    foreach ($_REQUEST as &$value) 
        $value = stripslashes($value);
}

config::init();

$filepath = ROOT . '/action/' . config::$action . '.php';
if (!file_exists($filepath) && !is_readable($filepath))
{
    display_msg("Bad action '" . config::$action . "'.");
}

require(ROOT . '/include/header.php');
require($filepath);
require(ROOT . '/include/footer.php');

exit();
