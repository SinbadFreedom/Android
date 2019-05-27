<?php
session_start();
/** 获取完整的来路URL 记录session,登陆完成后跳转回去*/
if (isset($_SERVER["HTTP_REFERER"])) {
    $url = $_SERVER["HTTP_REFERER"];
    $_SESSION['from_url'] = $url;
}
?>

<div align="center" style="margin-top: 3em">
    <button onclick="login_qq()"><img src="/img/Connect_logo_5.png"></button>
</div>
<div align="center" style="margin-top: 2em">
    <button onclick="login_wx()"><img src="/img/icon48_wx_button.png"></button>
</div>

<script>
    function login_qq() {
        window.location.href = "/lib/Connect2.1/example/oauth/index.php";
    }

    function login_wx() {
        var call_back_url = encodeURI('https://panda-doc.com/lib/wechat/login_wc_callback.php');
        window.location.href =
            'https://open.weixin.qq.com/connect/qrconnect?appid=wx7c369f8fe5340534&redirect_uri=' + call_back_url + '&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect';
    }
</script>