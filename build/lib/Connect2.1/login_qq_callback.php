<?php
require_once("API/qqConnectAPI.php");
$qc = new QC();
$access_token = $qc->qq_callback();
$open_id = $qc->get_openid();
//$url = 'https://graph.qq.com/user/get_user_info?access_token='. $access_token .'&oauth_consumer_key=101569922&openid='. $open_id .'';
$qc_info = new QC($access_token, $open_id);
$arr = $qc_info->get_user_info();
/**
 * B95E1C6C1D39AF7978AEBE978633382E
 * B1B2F5859B8CDE96F93531960D2D176B
 * Array ( [ret] => 0 [msg] => [is_lost] => 0 [nickname] => Sinbad [gender] => 男 [province] => 北京 [city] => [year] => 1982 [constellation] => [figureurl] => http://qzapp.qlogo.cn/qzapp/101569922/B1B2F5859B8CDE96F93531960D2D176B/30 [figureurl_1] => http://qzapp.qlogo.cn/qzapp/101569922/B1B2F5859B8CDE96F93531960D2D176B/50 [figureurl_2] => http://qzapp.qlogo.cn/qzapp/101569922/B1B2F5859B8CDE96F93531960D2D176B/100 [figureurl_qq_1] => http://thirdqq.qlogo.cn/g?b=oidb&k=mwBhEnLgHCziaHdoTau4KWw&s=40 [figureurl_qq_2] => http://thirdqq.qlogo.cn/g?b=oidb&k=mwBhEnLgHCziaHdoTau4KWw&s=100 [figureurl_qq] => http://thirdqq.qlogo.cn/g?b=oidb&k=mwBhEnLgHCziaHdoTau4KWw&s=140 [figureurl_type] => 1 [is_yellow_vip] => 0 [vip] => 0 [yellow_vip_level] => 0 [level] => 0 [is_yellow_year_vip] => 0 )
 */
$arr[nickname];
$arr[gender];
$arr[province];
$arr[city];
$arr[year];
$arr[figureurl_qq];
$arr[figureurl_type];

/** 登陆 */
