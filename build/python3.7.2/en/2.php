<?php
   session_start();
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
                if (isset($_SESSION['figureurl_qq'])) {
                    echo '<a class="nav-link" href="/php/user_info.php"><img src="' . $_SESSION['figureurl_qq'] . '" width="24px" height="24px"></a>';
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
        <a href="/index.php">首页</a>/<a href="catalog.php">&nbsppython3.7.2&nbsp</a>/&nbsp2
    </div>

    <div class="text-right">
        <a href="../zh_cn/2.php"><span>&nbsp简体&nbsp</span></a><a href="../en/2.php"><span>&nbspEnglish&nbsp</span></a>
    </div>

    <hr>

    <h1 id='2.'>2. Using the Python Interpreter</h1>
<h3 id="2.1.">2.1. Invoking the Interpreter</h3>
<p>The Python interpreter is usually installed as <code>/usr/local/bin/python3.7</code> on those machines where it is available; putting <code>/usr/local/bin</code> in your Unix shell's search path makes it possible to start it by typing the command:</p>
<pre class='prettyprint'><code>python3.7
</code></pre>
<p>to the shell. <a href="#">[1]</a> Since the choice of the directory where the interpreter lives is an installation option, other places are possible; check with your local Python guru or system administrator. (E.g., <code>/usr/local/python</code> is a popular alternative location.)</p>
<p>On Windows machines, the Python installation is usually placed in <code>C:\Python37</code>, though you can change this when you're running the installer. To add this directory to your path, you can type the following command into the command prompt in a DOS box:</p>
<pre class='prettyprint'><code>set path=%path%;C:\python37
</code></pre>
<p>Typing an end-of-file character (<code>Control-D</code> on Unix, <code>Control-Z</code>on Windows) at the primary prompt causes the interpreter to exit with a zero exit status. If that doesn't work, you can exit the interpreter by typing the following command: <code>quit()</code>.</p>
<p>The interpreter's line-editing features include interactive editing, history substitution and code completion on systems that support readline. Perhaps the quickest check to see whether command line editing is supported is typing <code>Control-P</code> to the first Python prompt you get. If it beeps, you have command line editing; see <a href="#">Appendix Interactive Input Editing and History Substitution</a> for an introduction to the keys. If nothing appears to happen, or if <code>^P</code> is echoed, command line editing isn't available; you'll only be able to use backspace to remove characters from the current line.</p>
<p>The interpreter operates somewhat like the Unix shell: when called with standard input connected to a tty device, it reads and executes commands interactively; when called with a file name argument or with a file as standard input, it reads and executes a script from that file.</p>
<p>A second way of starting the interpreter is <code>python -c command [arg] ...</code>, which executes the statement(s) in command, analogous to the shell's -c option. Since Python statements often contain spaces or other characters that are special to the shell, it is usually advised to quote command in its entirety with single quotes.</p>
<p>Some Python modules are also useful as scripts. These can be invoked using <code>python -m module [arg] ...</code>, which executes the source file for module as if you had spelled out its full name on the command line.</p>
<p>When a script file is used, it is sometimes useful to be able to run the script and enter interactive mode afterwards. This can be done by passing <a href="#">-i</a> before the script.</p>
<p>All command line options are described in <a href="#">Command line and environment</a>.</p>
<h4 id="2.1.1.">2.1.1. Argument Passing</h4>
<p>When known to the interpreter, the script name and additional arguments thereafter are turned into a list of strings and assigned to the <code>argv</code> variable in the <code>sys</code> module. You can access this list by executing <code>import sys</code>. The length of the list is at least one; when no script and no arguments are given, <code>sys.argv[0]</code> is an empty string. When the script name is given as '-' (meaning standard input), <code>sys.argv[0]</code> is set to <code>'-'</code>. When <a href="#">-c</a> command is used, <code>sys.argv[0]</code> is set to <code>'-c'</code>. When <a href="#">-m</a> module is used, <code>sys.argv[0]</code> is set to the full name of the located module. Options found after <a href="#">-c</a> command or <a href="#">-m</a> module are not consumed by the Python interpreter's option processing but left in <code>sys.argv</code>for the command or module to handle.</p>
<h4 id="2.1.2.">2.1.2. Interactive Mode</h4>
<p>When commands are read from a tty, the interpreter is said to be in interactive mode. In this mode it prompts for the next command with the primary prompt, usually three greater-than signs (<code>&gt;&gt;&gt;</code>); for continuation lines it prompts with the secondary prompt, by default three dots (<code>...</code>). The interpreter prints a welcome message stating its version number and a copyright notice before printing the first prompt:</p>
<pre class='prettyprint'><code>$ python3.7
Python 3.7 (default, Sep 16 2015, 09:25:04)
[GCC 4.8.2] on linux
Type "help", "copyright", "credits" or "license" for more information.
&gt;&gt;&gt;
</code></pre>
<p>Continuation lines are needed when entering a multi-line construct. As an example, take a look at this if statement:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; the_world_is_flat = True
&gt;&gt;&gt; if the_world_is_flat:
...     print("Be careful not to fall off!")
...
</code></pre>
<p>Be careful not to fall off!
For more on interactive mode, see <a href="#">Interactive Mode</a>.</p>
<h3 id="2.2.">2.2. The Interpreter and Its Environment</h3>
<h4 id="2.2.1.">2.2.1. Source Code Encoding</h4>
<p>By default, Python source files are treated as encoded in UTF-8. In that encoding, characters of most languages in the world can be used simultaneously in string literals, identifiers and comments - although the standard library only uses ASCII characters for identifiers, a convention that any portable code should follow. To display all these characters properly, your editor must recognize that the file is UTF-8, and it must use a font that supports all the characters in the file.</p>
<p>To declare an encoding other than the default one, a special comment line should be added as the first line of the file. The syntax is as follows:</p>
<pre class='prettyprint'><code># -*- coding: encoding -*-
</code></pre>
<p>where encoding is one of the valid <code>codecs</code> supported by Python.</p>
<p>For example, to declare that Windows-1252 encoding is to be used, the first line of your source code file should be:</p>
<pre class='prettyprint'><code># -*- coding: cp1252 -*-
</code></pre>
<p>One exception to the first line rule is when the source code starts with a UNIX "shebang" line. In this case, the encoding declaration should be added as the second line of the file. For example:</p>
<pre class='prettyprint'><code>#!/usr/bin/env python3
# -*- coding: cp1252 -*-
</code></pre>
<p><strong><em>Footnotes</em></strong></p>
<p><a href="#">[1]</a> On Unix, the Python 3.x interpreter is by default not installed with the executable named python, so that it does not conflict with a simultaneously installed Python 2.x executable.</p>

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