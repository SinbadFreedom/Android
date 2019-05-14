<?php
   session_start();
   require_once('../../php/update_exp.php');
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>熊猫文档-面向程序员的技术文档网站</title>
    <link rel="stylesheet" href="/lib/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <script src="/lib/google-code-prettify/run_prettify.js"></script>
    <link rel="stylesheet" href="/css/dashidan.css">
</head>
<body>

<div style="background: #2196F3">
    <img src="/img/web_1.png">
</div>

<nav class="navbar navbar-expand navbar-light">
    <div class="container">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link active" href="/index.php"><b>首页</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/php/forum/index.php"><b>笔记</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/php/rank_list.php"><b>排行榜</b></a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <?php
                if (isset($_SESSION['figure_url'])) {
                    echo '<a class="nav-link" href="/php/user_info.php"><img class="rounded" src="' . $_SESSION['figure_url'] . '" width="24px" height="24px"></a>';
                } else {
                echo '<a class="nav-link" href="/php/login_ui.php"><b>登录</b></a>';
                }
                ?>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div>
        <a href="/index.php">&nbsp首页&nbsp</a>/&nbsppython3.7.2
    </div>

    <div class="text-right">
        <a href="../zh_cn/catalog.php"><span>&nbsp简体&nbsp</span></a><a href="../en/catalog.php"><span>&nbspEnglish&nbsp</span></a>
    </div>

    <hr>

    <h2 id='Python3.7.2文档'>Python3.7.2文档</h2>
<h4 id="1.">1. <a href="1.php">磨砺你的胃口</a></h4>
<h4 id="2.">2. <a href="2.php">使用Python解释器</a></h4>
<ul>
<li>2.1. <a href="2.php#2.1.">调用口译员</a><ul>
<li>2.1.1. <a href="2.php#2.1.1.">参数传递</a></li>
<li>2.1.2. <a href="2.php#2.1.2.">交互模式</a></li></ul></li>
<li>2.2. <a href="2.php#2.2.">口译员及其环境</a><ul>
<li>2.2.1. <a href="2.php#2.2.1.">源代码编码</a></li></ul></li>
</ul>
<h4 id="3.">3. <a href="3.php">Python的非正式介绍</a></h4>
<ul>
<li>3.1. <a href="3.php#3.1.">使用Python作为计算器</a><br /><ul>
<li>3.1.1. <a href="3.php#3.1.1.">数()</a>   </li>
<li>3.1.2. <a href="3.php#3.1.2.">字符串()</a>   </li>
<li>3.1.3. <a href="3.php#3.1.3.">列表</a>   </li></ul></li>
<li>3.2. <a href="3.php#3.2.">编程的第一步</a>   </li>
</ul>
<h4 id="4.">4. <a href="4.php">更多控制流工具.</a></h4>
<ul>
<li>4.1. <a href="4.php#4.1.">如果声明</a>   </li>
<li>4.2. <a href="4.php#4.2.">for Statements</a>   </li>
<li>4.3. <a href="4.php#4.3.">范围()函数</a>   </li>
<li>4.4. <a href="4.php#4.4.">中断并继续声明,以及循环上的条款</a>   </li>
<li>4.5. <a href="4.php#4.5.">通过声明</a>   </li>
<li>4.6. <a href="4.php#4.6.">定义函数</a>   </li>
<li>4.7. <a href="4.php#4.7.">更多关于定义函数</a><br /><ul>
<li>4.7.1. <a href="4.php#4.7.1.">默认参数值</a>   </li>
<li>4.7.2. <a href="4.php#4.7.2.">Keyword Arguments</a>   </li>
<li>4.7.3. <a href="4.php#4.7.3.">任意参数列表</a>   </li>
<li>4.7.4. <a href="4.php#4.7.4.">打开参数列表</a>   </li>
<li>4.7.5. <a href="4.php#4.7.5.">Lambda表达式</a>   </li>
<li>4.7.6. <a href="4.php#4.7.6.">文档字符串</a>   </li>
<li>4.7.7. <a href="4.php#4.7.7.">功能注释</a>   </li></ul></li>
<li>4.8. <a href="4.php#4.8.">Intermezzo:编码风格</a>   </li>
</ul>
<h4 id="5.">5. <a href="5.php">数据结构</a></h4>
<ul>
<li>5.1. <a href="5.php#5.1.">更多关于列表</a><br /><ul>
<li>5.1.1. <a href="5.php#5.1.1.">使用列表作为堆栈</a>   </li>
<li>5.1.2. <a href="5.php#5.1.2.">使用列表作为队列</a>   </li>
<li>5.1.3. <a href="5.php#5.1.3.">列表理解</a>   </li>
<li>5.1.4. <a href="5.php#5.1.4.">嵌套列表理解</a>   </li></ul></li>
<li>5.2. <a href="5.php#5.2.">del声明</a>   </li>
<li>5.3. <a href="5.php#5.3.">元组和序列</a>   </li>
<li>5.4. <a href="5.php#5.4.">集</a>   </li>
<li>5.5. <a href="5.php#5.5.">字典</a>   </li>
<li>5.6. <a href="5.php#5.6.">循环技术</a>   </li>
<li>5.7. <a href="5.php#5.7.">有关条件的更多信息</a>   </li>
<li>5.8. <a href="5.php#5.8.">比较序列和其他类型</a>   </li>
</ul>
<h4 id="6.">6. <a href="6.php">模块</a>.</h4>
<ul>
<li>6.1. <a href="6.php#6.1.">更多关于模块</a><br /><ul>
<li>6.1.1. <a href="6.php#6.1.1.">将模块作为脚本执行</a>   </li>
<li>6.1.2. <a href="6.php#6.1.2.">模块搜索路径</a>   </li>
<li>6.1.3. <a href="6.php#6.1.3.">"编译的"Python文件</a>   </li></ul></li>
<li>6.2. <a href="6.php#6.2.">标准模块</a>   </li>
<li>6.3. <a href="6.php#6.3.">dir()函数</a>   </li>
<li>6.4. <a href="6.php#6.4.">软件包</a><br /><ul>
<li>6.4.1. <a href="6.php#6.4.1.">从包中导入*</a>   </li>
<li>6.4.2. <a href="6.php#6.4.2.">包内参考</a>   </li>
<li>6.4.3. <a href="6.php#6.4.2.">多个目录中的包</a>   </li></ul></li>
</ul>
<h4 id="7.">7. <a href="7.php">输入和输出</a></h4>
<ul>
<li>7.1. <a href="7.php#7.1.">Fancier输出格式</a><br /><ul>
<li>7.1.1. <a href="7.php#7.1.1.">Formatted String Literals</a>   </li>
<li>7.1.2. <a href="7.php#7.1.2.">String format()方法</a>   </li>
<li>7.1.3. <a href="7.php#7.1.3.">手动字符串格式</a>   </li>
<li>7.1.4. <a href="7.php#7.1.4.">旧字符串格式化</a>   </li></ul></li>
<li>7.2. <a href="7.php#7.2.">读写文件</a><br /><ul>
<li>7.2.1. <a href="7.php#7.2.1.">文件对象的方法</a>   </li>
<li>7.2.2. <a href="7.php#7.2.2.">用json保存结构化数据</a>   </li></ul></li>
</ul>
<h4 id="8.">8. <a href="8.php">错误和例外</a></h4>
<ul>
<li>8.1. <a href="8.php#8.1.">语法错误</a>   </li>
<li>8.2. <a href="8.php#8.2.">例外</a>   </li>
<li>8.3. <a href="8.php#8.3.">处理例外</a>   </li>
<li>8.4. <a href="8.php#8.4.">提高例外</a>   </li>
<li>8.5. <a href="8.php#8.5.">用户定义的例外</a>   </li>
<li>8.6. <a href="8.php#8.6.">定义清理行动</a>   </li>
<li>8.7. <a href="8.php#8.7.">预定义的清理行动</a>   </li>
</ul>
<h4 id="9.">9. <a href="9.php">类别</a>.</h4>
<ul>
<li>9.1. <a href="9.php#9.1.">关于名称和对象的一个​​词</a>   </li>
<li>9.2. <a href="9.php#9.2.">Python范围和命名空间</a><br /><ul>
<li>9.2.1. <a href="9.php#9.2.1.">范围和命名空间示例</a>   </li></ul></li>
<li>9.3. <a href="9.php#9.3.">初看班级</a><br /><ul>
<li>9.3.1. <a href="9.php#9.3.1.">类定义语法</a>   </li>
<li>9.3.2. <a href="9.php#9.3.2.">类对象</a>   </li>
<li>9.3.3. <a href="9.php#9.3.3.">实例对象</a>   </li>
<li>9.3.4. <a href="9.php#9.3.4.">方法对象</a>   </li>
<li>9.3.5. <a href="9.php#9.3.5.">类和实例变量</a>   </li></ul></li>
<li>9.4. <a href="9.php#9.4.">随机备注</a>   </li>
<li>9.5. <a href="9.php#9.5.">继承</a><br /><ul>
<li>9.5.1. <a href="9.php#9.5.1.">多重继承</a>   </li></ul></li>
<li>9.6. <a href="9.php#9.6.">私人变量</a>   </li>
<li>9.7. <a href="9.php#9.7.">赔率和结束</a>   </li>
<li>9.8. [迭代器(9.php#9.8.)   </li>
<li>9.9. <a href="9.php#9.9.">发电机</a>   </li>
<li>9.10. <a href="9.php#9.10.">Generator Expressions</a>   </li>
</ul>
<h4 id="10.">10. <a href="10.php">标准图书馆简介</a></h4>
<ul>
<li>10.1. <a href="10.php#10.1.">操作系统界面</a>   </li>
<li>10.2. <a href="10.php#10.2.">文件通配符</a>   </li>
<li>10.3. <a href="10.php#10.3.">命令行参数</a>   </li>
<li>10.4. <a href="10.php#10.4.">错误输出重定向和程序终止</a>   </li>
<li>10.5. <a href="10.php#10.5.">String Pattern Matching</a>   </li>
<li>10.6. <a href="10.php#10.6.">数学</a>   </li>
<li>10.7. <a href="10.php#10.7.">Internet Access</a>   </li>
<li>10.8. <a href="10.php#10.8.">日期和时间</a>   </li>
<li>10.9. <a href="10.php#10.9.">数据压缩</a>   </li>
<li>10.10. <a href="10.php#10.10.">绩效评估</a>   </li>
<li>10.11. <a href="10.php#10.11.">质量控制</a>   </li>
<li>10.12. <a href="10.php#10.12.">包含电池</a>   </li>
</ul>
<h4 id="11.">11. <a href="11.php">标准图书馆简介 - 第二部分</a></h4>
<ul>
<li>11.1. <a href="11.php#11.1.">输出格式</a>   </li>
<li>11.2. <a href="11.php#11.2.">模板</a>   </li>
<li>11.3. <a href="11.php#11.3.">使用二进制数据记录布局</a>   </li>
<li>11.4. <a href="11.php#11.4.">多线程</a>   </li>
<li>11.5. <a href="11.php#11.5.">记录</a>   </li>
<li>11.6. <a href="11.php#11.6.">弱参考</a>   </li>
<li>11.7. <a href="11.php#11.7.">使用列表的工具</a>   </li>
<li>11.8. <a href="11.php#11.8.">十进制浮点运算</a>   </li>
</ul>
<h4 id="12.">12. <a href="12.php">虚拟环境和软件包</a></h4>
<ul>
<li>12.1. <a href="12.php#12.1.">简介</a>   </li>
<li>12.2. <a href="12.php#12.2.">创建虚拟环境</a>   </li>
<li>12.3. <a href="12.php#12.3.">使用pip管理包</a>   </li>
</ul>
<h4 id="13.">13. <a href="13.php">现在怎么办?</a></h4>
<h4 id="14.">14. <a href="14.php">交互式输入编辑和历史替换</a></h4>
<ul>
<li>14.1. <a href="14.php#14.1.">标签完成和历史编辑</a>   </li>
<li>14.2. <a href="14.php#14.2.">交互式口译员的替代方案</a>   </li>
</ul>
<h4 id="15.">15. <a href="15.php">浮点运算:问题和局限</a></h4>
<ul>
<li>15.1. <a href="15.php#15.1.">表示错误</a>   </li>
</ul>
<h4 id="16.">16. <a href="16.php">附记</a>.</h4>
<ul>
<li>16.1. <a href="16.php#16.1.">交互模式</a><br /><ul>
<li>16.1.1. <a href="16.php#16.1.1.">错误处理</a>   </li>
<li>16.1.2. <a href="16.php#16.1.2.">可执行的Python脚本</a>   </li>
<li>16.1.3. <a href="16.php#16.1.3.">交互式启动文件</a>   </li>
<li>16.1.4. <a href="16.php#16.1.4.">定制模块</a>   </li></ul></li>
</ul>

    <h4>最新笔记</h4>

    <div id="note_area">
        <!-- 评论区-->
    </div>
</div>

<script src="/lib/jquery-3.2.1.min.js"></script>
<script>
    /** 评论*/
    var url = "/php/forum/index.php?tag=python3.7.2&show_header=0";
    $.ajax({
        url: url,
        type: "GET",
        async: false,//同步请求用false,异步请求true
        dataType: "html",
        data: {},
        success: function (data) {
            document.getElementById("note_area").innerHTML = data;
        },
        error: function (data, textstatus) {
            //请求不成功返回的提示
        }
    });
</script>
</body>
</html>