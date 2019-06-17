<?php
/** 调用查询信息的php页面，返回数据，通过模板生成html页面*/
$res = require_once('../forum/ask_info.php');

/** html模板数据*/
$res_obj = json_decode($res);
/** 数据成功*/
require_once('../../index.html');