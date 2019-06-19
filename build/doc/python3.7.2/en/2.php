<h1 id='2_'>2. Using the Python Interpreter</h1>
<h3 id="2_1_">2.1. Invoking the Interpreter</h3>
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
<h4 id="2_1_1_">2.1.1. Argument Passing</h4>
<p>When known to the interpreter, the script name and additional arguments thereafter are turned into a list of strings and assigned to the <code>argv</code> variable in the <code>sys</code> module. You can access this list by executing <code>import sys</code>. The length of the list is at least one; when no script and no arguments are given, <code>sys.argv[0]</code> is an empty string. When the script name is given as '-' (meaning standard input), <code>sys.argv[0]</code> is set to <code>'-'</code>. When <a href="#">-c</a> command is used, <code>sys.argv[0]</code> is set to <code>'-c'</code>. When <a href="#">-m</a> module is used, <code>sys.argv[0]</code> is set to the full name of the located module. Options found after <a href="#">-c</a> command or <a href="#">-m</a> module are not consumed by the Python interpreter's option processing but left in <code>sys.argv</code>for the command or module to handle.</p>
<h4 id="2_1_2_">2.1.2. Interactive Mode</h4>
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
<h3 id="2_2_">2.2. The Interpreter and Its Environment</h3>
<h4 id="2_2_1_">2.2.1. Source Code Encoding</h4>
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

<div class="text-right">
    当前有<?php echo mt_rand(0, 99); ?>位同学在看此文章
</div>

<div class="row center-block text-center">
    <div class="col-6 text-right">
            <button id="doc_last" class="btn btn-light" onclick="updateDocAndNote(1)">← 上一篇</button>
    </div>
    <div class="col-6 text-left">
            <button id="doc_next" class="btn btn-light" onclick="updateDocAndNote(3)">下一篇 →</button>
    </div>
</div>
