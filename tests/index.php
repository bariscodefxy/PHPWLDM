<?php

require_once "../vendor/autoload.php";

use phpwldm\PHPWLDM;

$wldm = new PHPWLDM();
echo $wldm->getTemplateEngine()->LoadFile("test");