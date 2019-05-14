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
        <a href="/index.php">首页</a>/<a href="catalog.php">&nbsppython3.7.2&nbsp</a>/&nbsp3
    </div>

    <div class="text-right">
        <a href="../zh_cn/3.php"><span>&nbsp简体&nbsp</span></a><a href="../en/3.php"><span>&nbspEnglish&nbsp</span></a>
    </div>

    <hr>

    <h1 id='3.'>3. An Informal Introduction to Python</h1>
<p>In the following examples, input and output are distinguished by the presence or absence of prompts (<a href="#">>>></a> and …): to repeat the example, you must type everything after the prompt, when the prompt appears; lines that do not begin with a prompt are output from the interpreter. Note that a secondary prompt on a line by itself in an example means you must type a blank line; this is used to end a multi-line command.</p>
<p>Many of the examples in this manual, even those entered at the interactive prompt, include comments. Comments in Python start with the hash character, #, and extend to the end of the physical line. A comment may appear at the start of a line or following whitespace or code, but not within a string literal. A hash character within a string literal is just a hash character. Since comments are to clarify code and are not interpreted by Python, they may be omitted when typing in examples.</p>
<p>Some examples:</p>
<pre class='prettyprint'><code># this is the first comment
spam = 1  # and this is the second comment
          # ... and now a third!
text = "# This is not a comment because it's inside quotes."
</code></pre>
<h3 id="3.1.">3.1. Using Python as a Calculator</h3>
<p>Let's try some simple Python commands. Start the interpreter and wait for the primary prompt, <code>&gt;&gt;&gt;</code>. (It shouldn't take long.)</p>
<h4 id="3.1.1.">3.1.1. Numbers</h4>
<p>The interpreter acts as a simple calculator: you can type an expression at it and it will write the value. Expression syntax is straightforward: the operators <code>+</code>, <code>-</code>, <code>*</code> and <code>/</code> work just like in most other languages (for example, Pascal or C); parentheses (<code>()</code>) can be used for grouping. For example:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; 2 + 2
4
&gt;&gt;&gt; 50 - 5*6
20
&gt;&gt;&gt; (50 - 5*6) / 4
5.0
&gt;&gt;&gt; 8 / 5  # division always returns a floating point number
1.6
</code></pre>
<p>The integer numbers (e.g. <code>2</code>, <code>4</code>, <code>20</code>) have type <a href="#">int</a>, the ones with a fractional part (e.g. <code>5.0</code>, <code>1.6</code>) have type <a href="#">float</a>. We will see more about numeric types later in the tutorial.</p>
<p>Division (<code>/</code>) always returns a float. To do <a href="#">floor division</a> and get an integer result (discarding any fractional result) you can use the <code>//</code> operator; to calculate the remainder you can use <code>%</code>:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; 17 / 3  # classic division returns a float
5.666666666666667
&gt;&gt;&gt;
&gt;&gt;&gt; 17 // 3  # floor division discards the fractional part
5
&gt;&gt;&gt; 17 % 3  # the % operator returns the remainder of the division
2
&gt;&gt;&gt; 5 * 3 + 2  # result * divisor + remainder
17
</code></pre>
<p>With Python, it is possible to use the <code>**</code> operator to calculate powers <a href="#">1</a>:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; 5 ** 2  # 5 squared
25
&gt;&gt;&gt; 2 ** 7  # 2 to the power of 7
128
</code></pre>
<p>The equal sign (<code>=</code>) is used to assign a value to a variable. Afterwards, no result is displayed before the next interactive prompt:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; width = 20
&gt;&gt;&gt; height = 5 * 9
&gt;&gt;&gt; width * height
900
</code></pre>
<p>If a variable is not "defined" (assigned a value), trying to use it will give you an error:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; n  # try to access an undefined variable
Traceback (most recent call last):
  File "&lt;stdin&gt;", line 1, in &lt;module&gt;
NameError: name 'n' is not defined
</code></pre>
<p>There is full support for floating point; operators with mixed type operands convert the integer operand to floating point:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; 4 * 3.75 - 1
14.0
</code></pre>
<p>In interactive mode, the last printed expression is assigned to the variable <code>_</code>. This means that when you are using Python as a desk calculator, it is somewhat easier to continue calculations, for example:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; tax = 12.5 / 100
&gt;&gt;&gt; price = 100.50
&gt;&gt;&gt; price * tax
12.5625
&gt;&gt;&gt; price + _
113.0625
&gt;&gt;&gt; round(_, 2)
113.06
</code></pre>
<p>This variable should be treated as read-only by the user. Don't explicitly assign a value to it - you would create an independent local variable with the same name masking the built-in variable with its magic behavior.</p>
<p>In addition to <a href="#">int</a> and <a href="#">float</a>, Python supports other types of numbers, such as <a href="#">Decimal</a> and <a href="#">Fraction</a>. Python also has built-in support for <a href="#">complex numbers</a>, and uses the j or J suffix to indicate the imaginary part (e.g. <code>3+5j</code>).</p>
<h4 id="3.1.2.">3.1.2. Strings</h4>
<p>Besides numbers, Python can also manipulate strings, which can be expressed in several ways. They can be enclosed in single quotes (<code>'...'</code>) or double quotes (<code>"..."</code>) with the same result [2]. \ can be used to escape quotes:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; 'spam eggs'  # single quotes
'spam eggs'
&gt;&gt;&gt; 'doesn\'t'  # use \' to escape the single quote...
"doesn't"
&gt;&gt;&gt; "doesn't"  # ...or use double quotes instead
"doesn't"
&gt;&gt;&gt; '"Yes," they said.'
'"Yes," they said.'
&gt;&gt;&gt; "\"Yes,\" they said."
'"Yes," they said.'
&gt;&gt;&gt; '"Isn\'t," they said.'
'"Isn\'t," they said.'
</code></pre>
<p>In the interactive interpreter, the output string is enclosed in quotes and special characters are escaped with backslashes. While this might sometimes look different from the input (the enclosing quotes could change), the two strings are equivalent. The string is enclosed in double quotes if the string contains a single quote and no double quotes, otherwise it is enclosed in single quotes. The <a href="#">print()</a> function produces a more readable output, by omitting the enclosing quotes and by printing escaped and special characters:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; '"Isn\'t," they said.'
'"Isn\'t," they said.'
&gt;&gt;&gt; print('"Isn\'t," they said.')
"Isn't," they said.
&gt;&gt;&gt; s = 'First line.\nSecond line.'  # \n means newline
&gt;&gt;&gt; s  # without print(), \n is included in the output
'First line.\nSecond line.'
&gt;&gt;&gt; print(s)  # with print(), \n produces a new line
First line.
Second line.
</code></pre>
<p>If you don't want characters prefaced by <code>\</code> to be interpreted as special characters, you can use raw strings by adding an r before the first quote:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; print('C:\some\name')  # here \n means newline!
C:\some\name
&gt;&gt;&gt; print(r'C:\some\name')  # note the r before the quote
C:\some\name
</code></pre>
<p>String literals can span multiple lines. One way is using triple-quotes: <code>"""..."""</code> or <code>'''...'''</code>. End of lines are automatically included in the string, but it's possible to prevent this by adding a <code>\</code> at the end of the line. The following example:</p>
<pre class='prettyprint'><code>print("""\
Usage: thingy [OPTIONS]
     -h                        Display this usage message
     -H hostname               Hostname to connect to
""")
</code></pre>
<p>produces the following output (note that the initial newline is not included):</p>
<pre class='prettyprint'><code>Usage: thingy [OPTIONS]
     -h                        Display this usage message
     -H hostname               Hostname to connect to
</code></pre>
<p>Strings can be concatenated (glued together) with the <code>+</code> operator, and repeated with <code>*</code>:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; # 3 times 'un', followed by 'ium'
&gt;&gt;&gt; 3 * 'un' + 'ium'
'unununium'
</code></pre>
<p>Two or more string literals (i.e. the ones enclosed between quotes) next to each other are automatically concatenated.</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; 'Py' 'thon'
'Python'
</code></pre>
<p>This feature is particularly useful when you want to break long strings:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; text = ('Put several strings within parentheses '
...         'to have them joined together.')
&gt;&gt;&gt; text
'Put several strings within parentheses to have them joined together.'
</code></pre>
<p>This only works with two literals though, not with variables or expressions:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; prefix = 'Py'
&gt;&gt;&gt; prefix 'thon'  # can't concatenate a variable and a string literal
  File "&lt;stdin&gt;", line 1
    prefix 'thon'
                ^
SyntaxError: invalid syntax
&gt;&gt;&gt; ('un' * 3) 'ium'
  File "&lt;stdin&gt;", line 1
    ('un' * 3) 'ium'
                   ^
SyntaxError: invalid syntax
</code></pre>
<p>If you want to concatenate variables or a variable and a literal, use <code>+</code>:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; prefix + 'thon'
'Python'
</code></pre>
<p>Strings can be indexed (subscripted), with the first character having index 0. There is no separate character type; a character is simply a string of size one:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word = 'Python'
&gt;&gt;&gt; word[0]  # character in position 0
'P'
&gt;&gt;&gt; word[5]  # character in position 5
'n'
</code></pre>
<p>Indices may also be negative numbers, to start counting from the right:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word[-1]  # last character
'n'
&gt;&gt;&gt; word[-2]  # second-last character
'o'
&gt;&gt;&gt; word[-6]
'P'
</code></pre>
<p>Note that since -0 is the same as 0, negative indices start from -1.</p>
<p>In addition to indexing, slicing is also supported. While indexing is used to obtain individual characters, slicing allows you to obtain substring:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word[0:2]  # characters from position 0 (included) to 2 (excluded)
'Py'
&gt;&gt;&gt; word[2:5]  # characters from position 2 (included) to 5 (excluded)
'tho'
</code></pre>
<p>Note how the start is always included, and the end always excluded. This makes sure that <code>s[:i] + s[i:]</code> is always equal to <code>s</code>:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word[:2] + word[2:]
'Python'
&gt;&gt;&gt; word[:4] + word[4:]
'Python'
</code></pre>
<p>Slice indices have useful defaults; an omitted first index defaults to zero, an omitted second index defaults to the size of the string being sliced.</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word[:2]   # character from the beginning to position 2 (excluded)
'Py'
&gt;&gt;&gt; word[4:]   # characters from position 4 (included) to the end
'on'
&gt;&gt;&gt; word[-2:]  # characters from the second-last (included) to the end
'on'
</code></pre>
<p>One way to remember how slices work is to think of the indices as pointing between characters, with the left edge of the first character numbered 0. Then the right edge of the last character of a string of n characters has index n, for example:</p>
<pre class='prettyprint'><code> +---+---+---+---+---+---+
 | P | y | t | h | o | n |
 +---+---+---+---+---+---+
 0   1   2   3   4   5   6
-6  -5  -4  -3  -2  -1
</code></pre>
<p>The first row of numbers gives the position of the indices 0…6 in the string; the second row gives the corresponding negative indices. The slice from i to j consists of all characters between the edges labeled i and j, respectively.</p>
<p>For non-negative indices, the length of a slice is the difference of the indices, if both are within bounds. For example, the length of <code>word[1:3]</code> is 2.</p>
<p>Attempting to use an index that is too large will result in an error:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word[42]  # the word only has 6 characters
Traceback (most recent call last):
  File "&lt;stdin&gt;", line 1, in &lt;module&gt;
IndexError: string index out of range
</code></pre>
<p>However, out of range slice indexes are handled gracefully when used for slicing:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word[4:42]
'on'
&gt;&gt;&gt; word[42:]
''
</code></pre>
<p>Python strings cannot be changed - they are <a href="#">immutable</a>. Therefore, assigning to an indexed position in the string results in an error:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word[0] = 'J'
Traceback (most recent call last):
  File "&lt;stdin&gt;", line 1, in &lt;module&gt;
TypeError: 'str' object does not support item assignment
&gt;&gt;&gt; word[2:] = 'py'
Traceback (most recent call last):
  File "&lt;stdin&gt;", line 1, in &lt;module&gt;
TypeError: 'str' object does not support item assignment
</code></pre>
<p>If you need a different string, you should create a new one:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; 'J' + word[1:]
'Jython'
&gt;&gt;&gt; word[:2] + 'py'
'Pypy'
</code></pre>
<p>The built-in function <a href="#">len()</a> returns the length of a string:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; s = 'supercalifragilisticexpialidocious'
&gt;&gt;&gt; len(s)
34
</code></pre>
<p>See also</p>
<p><a href="#">Text Sequence Type - str</a></p>
<p>Strings are examples of sequence types, and support the common operations supported by such types.</p>
<p><a href="#">String Methods</a></p>
<p>Strings support a large number of methods for basic transformations and searching.</p>
<p><a href="#">Formatted string literals</a></p>
<p>String literals that have embedded expressions.</p>
<p><a href="#">Format String Syntax</a></p>
<p>Information about string formatting with str.format().</p>
<p><a href="#">printf-style String Formatting</a></p>
<p>The old formatting operations invoked when strings are the left operand of the % operator are described in more detail here.</p>
<h4 id="3.1.3.">3.1.3. Lists</h4>
<p>Python knows a number of compound data types, used to group together other values. The most versatile is the list, which can be written as a list of comma-separated values (items) between square brackets. Lists might contain items of different types, but usually the items all have the same type.</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; squares = [1, 4, 9, 16, 25]
&gt;&gt;&gt; squares
[1, 4, 9, 16, 25]
</code></pre>
<p>Like strings (and all other built-in <a href="#">sequence</a> type), lists can be indexed and sliced:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; squares[0]  # indexing returns the item
1
&gt;&gt;&gt; squares[-1]
25
&gt;&gt;&gt; squares[-3:]  # slicing returns a new list
[9, 16, 25]
</code></pre>
<p>All slice operations return a new list containing the requested elements. This means that the following slice returns a new (shallow) copy of the list:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; squares[:]
[1, 4, 9, 16, 25]
</code></pre>
<p>Lists also support operations like concatenation:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; squares + [36, 49, 64, 81, 100]
[1, 4, 9, 16, 25, 36, 49, 64, 81, 100]
</code></pre>
<p>Unlike strings, which are <a href="#">immutable</a>, lists are a <a href="#">mutable</a> type, i.e. it is possible to change their content:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; cubes = [1, 8, 27, 65, 125]  # something's wrong here
&gt;&gt;&gt; 4 ** 3  # the cube of 4 is 64, not 65!
64
&gt;&gt;&gt; cubes[3] = 64  # replace the wrong value
&gt;&gt;&gt; cubes
[1, 8, 27, 64, 125]
</code></pre>
<p>You can also add new items at the end of the list, by using the <a href="#">append()</a> method (we will see more about methods later):</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; cubes.append(216)  # add the cube of 6
&gt;&gt;&gt; cubes.append(7 ** 3)  # and the cube of 7
&gt;&gt;&gt; cubes
[1, 8, 27, 64, 125, 216, 343]
</code></pre>
<p>Assignment to slices is also possible, and this can even change the size of the list or clear it entirely:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g']
&gt;&gt;&gt; letters
['a', 'b', 'c', 'd', 'e', 'f', 'g']
&gt;&gt;&gt; # replace some values
&gt;&gt;&gt; letters[2:5] = ['C', 'D', 'E']
&gt;&gt;&gt; letters
['a', 'b', 'C', 'D', 'E', 'f', 'g']
&gt;&gt;&gt; # now remove them
&gt;&gt;&gt; letters[2:5] = []
&gt;&gt;&gt; letters
['a', 'b', 'f', 'g']
&gt;&gt;&gt; # clear the list by replacing all the elements with an empty list
&gt;&gt;&gt; letters[:] = []
&gt;&gt;&gt; letters
[]
</code></pre>
<p>The built-in function <a href="#">len()</a> also applies to lists:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; letters = ['a', 'b', 'c', 'd']
&gt;&gt;&gt; len(letters)
4
</code></pre>
<p>It is possible to nest lists (create lists containing other lists), for example:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; a = ['a', 'b', 'c']
&gt;&gt;&gt; n = [1, 2, 3]
&gt;&gt;&gt; x = [a, n]
&gt;&gt;&gt; x
[['a', 'b', 'c'], [1, 2, 3]]
&gt;&gt;&gt; x[0]
['a', 'b', 'c']
&gt;&gt;&gt; x[0][1]
'b'
</code></pre>
<h3 id="3.2.">3.2. First Steps Towards Programming</h3>
<p>Of course, we can use Python for more complicated tasks than adding two and two together. For instance, we can write an initial sub-sequence of the <a href="#">Fibonacci series</a> as follows:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; # Fibonacci series:
... # the sum of two elements defines the next
... a, b = 0, 1
&gt;&gt;&gt; while a &lt; 10:
...     print(a)
...     a, b = b, a+b
...
0
1
1
2
3
5
8
</code></pre>
<p>This example introduces several new features.</p>
<ul>
<li><p>The first line contains a multiple assignment: the variables <code>a</code> and <code>b</code> simultaneously get the new values 0 and 1. On the last line this is used again, demonstrating that the expressions on the right-hand side are all evaluated first before any of the assignments take place. The right-hand side expressions are evaluated from the left to the right.</p></li>
<li><p>The <a href="#">while</a> loop executes as long as the condition (here: <code>a &lt; 10</code>) remains true. In Python, like in C, any non-zero integer value is true; zero is false. The condition may also be a string or list value, in fact any sequence; anything with a non-zero length is true, empty sequences are false. The test used in the example is a simple comparison. The standard comparison operators are written the same as in C: <code>&lt;</code> (less than), <code>&gt;</code> (greater than), <code>==</code> (equal to), <code>&lt;=</code> (less than or equal to), <code>&gt;=</code> (greater than or equal to) and <code>!=</code> (not equal to).</p></li>
<li><p>The body of the loop is indented: indentation is Python's way of grouping statements. At the interactive prompt, you have to type a tab or space(s) for each indented line. In practice you will prepare more complicated input for Python with a text editor; all decent text editors have an auto-indent facility. When a compound statement is entered interactively, it must be followed by a blank line to indicate completion (since the parser cannot guess when you have typed the last line). Note that each line within a basic block must be indented by the same amount.</p></li>
<li><p>The <a href="#">print()</a> function writes the value of the argument(s) it is given. It differs from just writing the expression you want to write (as we did earlier in the calculator examples) in the way it handles multiple arguments, floating point quantities, and strings. Strings are printed without quotes, and a space is inserted between items, so you can format things nicely, like this:</p></li>
</ul>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; i = 256*256
&gt;&gt;&gt; print('The value of i is', i)
The value of i is 65536
</code></pre>
<p>The keyword argument end can be used to avoid the newline after the output, or end the output with a different string:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; a, b = 0, 1
&gt;&gt;&gt; while a &lt; 1000:
...     print(a, end=',')
...     a, b = b, a+b
...
0,1,1,2,3,5,8,13,21,34,55,89,144,233,377,610,987,
</code></pre>
<h2 id='Footnotes'>Footnotes</h2>
<p><a href="#">1</a> Since <code>**</code> has higher precedence than <code>-</code>, <code>-3**2</code> will be interpreted as <code>-(3**2)</code> and thus result in <code>-9</code>. To avoid this and get <code>9</code>, you can use <code>(-3)**2</code>.</p>
<p><a href="#">2</a> Unlike other languages, special characters such as <code>\n</code> have the same meaning with both single (<code>'...'</code>) and double (<code>"..."</code>) quotes. The only difference between the two is that within single quotes you don't need to escape <code>"</code> (but you have to escape <code>\'</code>) and vice versa.</p>

    <h4>笔记</h4>

    <hr>

    <div id="note_area">
        <!-- 评论区-->
    </div>

    <div>
        <a href="/index.php">&nbsp熊猫文档&nbsp</a>/<a href="catalog.php">&nbsppython3.7.2
        &nbsp</a>/&nbsp3
    </div>

    <div class="text-right">
        当前有<?php echo mt_rand(0, 99); ?>位同学在看此文章
    </div>
</div>

<div class="row center-block text-center">
    <div class="col-6 text-right">
            <a href="2.php" class="badge badge-primary">← 上一篇</a>
            </div>
    <div class="col-6 text-left">
            <a href="4.php" class="badge badge-primary"> 下一篇 →</a>
    </div>
</div>

<script src="/lib/jquery-3.2.1.min.js"></script>
<script>
    /** 评论*/
    var url = "/php/forum/note_get.php?tag=python3.7.2&contentid=3&show_header=0";
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