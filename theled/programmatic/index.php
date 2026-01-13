<?php
session_start();
require_once('./config/security.config.php');

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once("./lib/controller.inc.php")
/******
 * DAO(s) deve(m) ser carregado(s) no módulo específico
 */
//require_once("./api/$currentPage/dao.$currentModule.list");
?>
<HTML>
<?php
require_once('./resources/views/header.inc.php');
require_once('./resources/views/body.inc.php');
require_once('./resources/views/footer.inc.php');
?>
</HTML>