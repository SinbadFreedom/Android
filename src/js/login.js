function loginLoadSuccess(res) {
    console.log('loginLoadSuccess');
    /** 替换登录页面框架*/
    refreshContentArea(res);

    /** 事件初始化*/
    $('#login_qq').off('click', loginClickBtnQQ);
    $('#login_wx').off('click', loginClickBtnWX);

    $('#login_qq').on('click', loginClickBtnQQ);
    $('#login_wx').on('click', loginClickBtnWX);
}

/** qq登录*/
function loginClickBtnQQ() {
    window.location.href = "/lib/Connect2.1/example/oauth/index.php";
}

/** wx登录*/
function loginClickBtnWX() {
    var call_back_url = encodeURI('https://panda-doc.com/lib/wechat/login_wc_callback.php');
    window.location.href =
        'https://open.weixin.qq.com/connect/qrconnect?appid=wx7c369f8fe5340534&redirect_uri=' + call_back_url + '&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect';
}
