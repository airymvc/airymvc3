<?php
/**
 * AiryMVC Framework
 * 
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

namespace airymvc;
use airymvc\core\Bootstrap;
use airymvc\core\Dispatch;
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR ."Bootstrap.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR ."Dispatch.php";


Bootstrap::init();
Dispatch::run();
?>
