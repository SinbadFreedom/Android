<?php
require_once("API/qqConnectAPI.php");
$qc = new QC();
echo $qc->qq_callback();
echo '--------------------<br>';
echo $qc->get_openid();
echo '--------------------<br>';
$ret = $qc->get_info();
print_r($ret);