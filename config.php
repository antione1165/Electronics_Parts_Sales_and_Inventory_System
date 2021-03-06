<?php
/**
 * Front controller for config view / download and clear
 */

declare(strict_types=1);

use PhpMyAdmin\Config\Forms\Setup\ConfigForm;
use PhpMyAdmin\Core;
use PhpMyAdmin\Response;
use PhpMyAdmin\Setup\ConfigGenerator;
use PhpMyAdmin\Url;

if (! defined('ROOT_PATH')) {
    // phpcs:disable PSR1.Files.SideEffects
    define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
    // phpcs:enable
}

/**
 * Core libraries.
 */
require ROOT_PATH . 'setup/lib/common.inc.php';

$form_display = new ConfigForm($GLOBALS['ConfigFile']);
$form_display->save('Config');

$response = Response::getInstance();
$response->disable();

if (isset($_POST['eol'])) {
    $_SESSION['eol'] = $_POST['eol'] === 'unix' ? 'unix' : 'win';
}

if (Core::ifSetOr($_POST['submit_clear'], '')) {
    // Clear current config and return to main page
    $GLOBALS['ConfigFile']->resetConfigData();
    // drop post data
    $response->generateHeader303('index.php' . Url::getCommonRaw());
    exit;
}

if (Core::ifSetOr($_POST['submit_download'], '')) {
    // Output generated config file
    Core::downloadHeader('config.inc.php', 'text/plain');
    $response->disable();
    echo ConfigGenerator::getConfigFile($GLOBALS['ConfigFile']);
    exit;
}

// Show generated config file in a <textarea>
$response->generateHeader303('index.php' . Url::getCommonRaw(['page' => 'config']));
/*For My LocalPC*/
//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;
// Connect to DB
$con = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
//$con=mysqli_connect ("localhost", "root", "wolf") or die ('I cannot connect to the database because: ' . mysql_error());
//mysqli_select_db ($con,'sampleLoginDB');
/* Heroku remote server */
$i++;
$cfg["Servers"][$i]["host"] = "us-cdbr-east-04.cleardb.net"; //provide hostname
$cfg["Servers"][$i]["user"] = "b3775ea834ed8a"; //user name for your remote server
$cfg["Servers"][$i]["password"] = "e9b72db3"; //password
$cfg["Servers"][$i]["auth_type"] = "config"; // keep it as config
exit;
