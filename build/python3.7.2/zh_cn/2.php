<!doctype html>
<html>
<head>
    <meta charset="utf-8"/>
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
            <li class="nav-item">
                <a class="nav-link" href="/php/login.php"><b>登录</b></a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">

    <div>
        <a href="/index.php">首页</a>/<a href="catalog.php">&nbsppython3.7.2&nbsp</a>/&nbsp2
    </div>

    <div class="text-right">
        <a href="../zh_cn/2.php"><span>&nbsp简体&nbsp</span></a><a href="../en/2.php"><span>&nbspEnglish&nbsp</span></a>
    </div>

    <hr>

    <h1 id='2.'>2. 使用Python解释器</h1>
<h3 id="2.1.">2.1. 调用口译员</h3>
<p>Python解释器通常在那些可用的机器上安装为<code>/ usr / local / bin / python3.7</code>;在你的Unix shell的搜索路径中放置<code>/ usr / local / bin</code>可以通过输入命令来启动它:</p>
<pre class='prettyprint'><code>python3.7
</code></pre>
<p>到了shell.  <a href="#">[1]</a>由于选择解释器所在的目录是一个安装选项,其他地方是可能的;请咨询您当地的Python大师或系统管理员.  (例如,``/ usr / local / python```是一个受欢迎的替代位置. )</p>
<p>在Windows机器上,Python安装通常放在<code>C:\ Python37</code>中,尽管你可以在运行安装程序时更改它. 要将此目录添加到路径,可以在DOS框中的命令提示符中键入以下命令:</p>
<pre class='prettyprint'><code>set path =%path%; C:\ python37
</code></pre>
<p>在主要提示符下键入一个文件结尾字符(在Unix上为<code>,在Windows上为</code>)会导致解释器以零退出状态退出. 如果这不起作用,您可以通过键入以下命令退出解释器:<code>quit()</code>. </p>
<p>解释器的行编辑功能包括支持readline的系统上的交互式编辑,历史替换和代码完成. 也许最快的检查是否支持命令行编辑是在你得到的第一个Python提示符下键入<code>Control-P</code>. 如果发出哔哔声,您可以进行命令行编辑;有关键的介绍,请参阅<a href="#">附录交互式输入编辑和历史替换</a>. 如果没有发生任何事情,或者回显"`^ P```,则命令行编辑不可用;你只能使用退格键从当前行中删除字符. </p>
<p>解释器的操作有点像Unix shell:当使用连接到tty设备的标准输入调用时,它以交互方式读取和执行命令;当使用文件名参数或文件作为标准输入调用时,它会从该文件中读取并执行脚本. </p>
<p>启动解释器的第二种方法是<code>python -c command [arg] ...</code>,它在命令中执行语句,类似于shell的-c选项. 由于Python语句通常包含空格或shell特有的其他字符,因此通常建议使用单引号引用命令. </p>
<p>一些Python模块也可用作脚本. 这些可以使用<code>python -m module [arg] ...</code>来调用,它执行模块的源文件,就像你在命令行中拼写出它的全名一样. </p>
<p>使用脚本文件时,有时可以运行脚本并在之后进入交互模式. 这可以通过在脚本之前传递<a href="#">-i</a>来完成. </p>
<p>所有命令行选项在<a href="#">命令行和环境</a>中描述. </p>
<h4 id="2.1.1.">2.1.1. 论证传递</h4>
<p>当解释器知道时,脚本名称和其后的附加参数将变成一个字符串列表,并分配给<code>sys</code>模块中的<code>argv</code>变量. 您可以通过执行<code>import sys</code>来访问此列表. 列表的长度至少为一;当没有给出脚本和参数时,<code>sys.argv [0]</code>是一个空字符串. 当脚本名称为" - "(表示标准输入)时,<code>sys.argv [0]</code>设置为<code>' - '</code>. 当使用<a href="#">-c</a>命令时,<code>sys.argv [0]</code>设置为<code>' -  c'</code>. 当使用<a href="#">-m</a>模块时,<code>sys.argv [0]</code>设置为所定位模块的全名. 在<a href="#">-c</a>命令或<a href="#">-m</a>模块之后找到的选项不会被Python解释器的选项处理所消耗,而是留在<code>sys.argv</code>中以供处理的命令或模块使用. </p>
<h4 id="2.1.2.">2.1.2. 交互模式</h4>
<p>当从tty读取命令时,解释器被称为处于交互模式. 在这种模式下,它会提示下一个带有主要提示的命令,通常是三个大于号(<code>&gt;&gt;</code>);对于延续线,它会提示辅助提示,默认为三个点(<code>...</code>). 在打印第一个提示之前,解释程序会打印一条欢迎消息,说明其版本号和版权声明:</p>
<pre class='prettyprint'><code>$ python3.7
Python 3.7(默认,2015年9月16日,09:25:04)
Linux上的[GCC 4.8.2]
输入"帮助","版权","信用"或"许可"以获取更多信息. 
&gt;&gt;&gt;
</code></pre>
<p>进入多线构造时需要延续线. 举个例子,看看这个if语句:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; the_world_is_flat =真
&gt;&gt;&gt;如果the_world_is_flat:
...打印("小心不要脱落!")
...
</code></pre>
<p>小心不要掉下来!
有关交互模式的更多信息,请参阅<a href="#">交互模式</a>. </p>
<h3 id="2.2.">2.2. 口译员及其环境</h3>
<h4 id="2.2.1.">2.2.1. 源代码编码</h4>
<p>默认情况下,Python源文件被视为以UTF-8编码. 在该编码中,世界上大多数语言的字符可以在字符串文字,标识符和注释中同时使用 - 尽管标准库仅使用ASCII字符作为标识符,这是任何可移植代码都应遵循的约定. 要正确显示所有这些字符,编辑器必须识别该文件是UTF-8,并且必须使用支持文件中所有字符的字体. </p>
<p>要声明非默认编码,应添加一个特殊注释行作为文件的第一行. 语法如下:</p>
<pre class='prettyprint'><code># -  *  - 编码:编码 -  *  - 
</code></pre>
<p>其中encoding是Python支持的有效<code>codecs</code>之一. </p>
<p>例如,要声明要使用Windows-1252编码,源代码文件的第一行应为:</p>
<pre class='prettyprint'><code># -  *  - 编码:cp1252  -  *  - 
</code></pre>
<p>第一行规则的一个例外是源代码以UNIX"shebang"行开头. 在这种情况下,应将编码声明添加为文件的第二行. 例如:</p>
<pre class='prettyprint'><code>#!/ usr / bin / env python3
# -  *  - 编码:cp1252  -  *  - 
</code></pre>
<p>*** ***脚注</p>
<p><a href="#">[1]</a> 在Unix上,Python 3.x解释器默认情况下不安装名为python的可执行文件,因此它不会与同时安装的Python 2.x可执行文件冲突.</p>

    <h4>笔记</h4>

    <hr>

    <div id="note_area">
        <!-- 评论区-->
    </div>

    <div>
        <a href="/index.php">&nbsp熊猫文档&nbsp</a>/<a href="catalog.php">&nbsppython3.7.2
        &nbsp</a>/&nbsp2
    </div>

    <div class="text-right">
        当前有<?php echo mt_rand(0, 99); ?>位同学在看此文章
    </div>
</div>

<div class="row center-block text-center">
    <div class="col-6 text-right">
            <a href="1.php" class="badge badge-primary">← 上一篇</a>
            </div>
    <div class="col-6 text-left">
            <a href="3.php" class="badge badge-primary"> 下一篇 →</a>
    </div>
</div>

<script src="/lib/jquery-3.2.1.min.js"></script>
<script>
    /** 评论*/
    var url = "/php/forum/content_get.php?tag=python3.7.2&contentid=2";
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