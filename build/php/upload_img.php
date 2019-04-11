<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/28
 * Time: 10:41
 */

if (!isset($_POST['openid'])) {
    echo "param error 0";
    return;
}
if (!isset($_POST['imgdata'])) {
    echo "param error 1";
    return;
}

$openid = $_POST['openid'];
$img_data = $_POST['imgdata'];

/** 记录log*/
$time_stamp = time();
$file = 'log_upload_img_' . date('Y-m-d', $time_stamp) . '.txt';
$content = $openid . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);

$decoded_image = base64_decode($img_data);
/** 需要提前创建好 上级 head_img目录，并且设置目录权限 777 */
$file_name = "../head_img/" . $openid . ".jpg";
$return = file_put_contents($file_name, $decoded_image);

var_dump($return);