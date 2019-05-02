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

    <h1 id='3.'>3. Python的非正式简介</h1>
<p>在以下示例中,输入和输出通过是否存在提示来区分(<a href="#">>>></a>和…):要重复示例,必须在提示符后出现提示时出现提示;从解释器输出不以提示开头的行. 请注意,示例中一行上的辅助提示意味着您必须键入一个空行;这用于结束多行命令. </p>
<p>本手册中的许多示例,即使是在交互式提示符下输入的示例,都包含注释.  Python中的注释以井号字符#开头,并延伸到物理行的末尾. 注释可能出现在行的开头或跟随空格或代码,但不在字符串文字中. 字符串文字中的哈希字符只是一个哈希字符. 由于注释是为了澄清代码而不是由Python解释,因此在键入示例时可能会省略它们. </p>
<p>一些例子:</p>
<pre class='prettyprint'><code>#这是第一条评论
spam = 1#,这是第二条评论
&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;#...现在是第三个!
text ="#这不是评论,因为它在引号内. "
</code></pre>
<h3 id="3.1.">3.1. 使用Python作为计算器</h3>
<p>让我们尝试一些简单的Python命令. 启动解释器并等待主要提示符<code>&gt;&gt;&gt;</code>.  (不应该花很长时间. )</p>
<h4 id="3.1.1.">3.1.1. 数字</h4>
<p>解释器充当一个简单的计算器:您可以在其上键入表达式,它将写入值. 表达式语法很简单:运算符<code>+</code>,<code>-</code>,<code>*</code>和<code>/</code>就像大多数其他语言一样工作(例如,Pascal或C);括号(<code>()</code>)可用于分组. 例如:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; 2 + 2
4
&gt;&gt;&gt; 50  -  5 * 6
20
&gt;&gt;&gt;(50  -  5 * 6)/ 4
5
&gt;&gt;&gt; 8/5#division始终返回浮点数
1.6
</code></pre>
<p>整数(例如<code>2</code>,<code>4</code>,<code>20</code>)具有类型<a href="#">int</a>,具有小数部分的那些(例如<code>5.0</code>,<code>1.6</code>)具有类型<a href="#">float</a> . 我们将在本教程后面看到有关数值类型的更多信息. </p>
<p>除(<code>/</code>)总是返回一个浮点数. 要做<a href="#">floor division</a>并获得整数结果(丢弃任何小数结果),你可以使用<code>//</code>运算符;计算余数你可以使用<code>%</code>:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; 17/3#经典师返回浮动
5.666666666666667
&gt;&gt;&gt;
&gt;&gt;&gt; 17 // 3#floor division丢弃小数部分
五
&gt;&gt;&gt; 17%3#%运算符返回除法的余数
2
&gt;&gt;&gt; 5 * 3 + 2#结果*除数+余数
17
</code></pre>
<p>使用Python,可以使用<code>**</code>运算符来计算幂<a href="#">1</a>:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; 5 ** 2#5平方
25
&gt;&gt;&gt; 2 ** 7#2的力量为7
128
</code></pre>
<p>等号(<code>=</code>)用于为变量赋值. 之后,在下一个交互式提示之前不会显示任何结果:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;宽度= 20
&gt;&gt;&gt;身高= 5 * 9
&gt;&gt;&gt;宽*高
900
</code></pre>
<p>如果变量没有"定义"(赋值),尝试使用它会给你一个错误:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; n#尝试访问未定义的变量
Traceback(最近一次调用最后一次):
&amp;nbsp;&amp;nbsp;在&lt;module&gt;中的文件"&lt;stdin&gt;",第1行
NameError:未定义名称"n"
</code></pre>
<p>浮点数完全支持;具有混合类型操作数的运算符将整数操作数转换为浮点数:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; 4 * 3.75  -  1
14.0
</code></pre>
<p>在交互模式下,最后打印的表达式被赋值给变量<code>_</code>. 这意味着当您使用Python作为桌面计算器时,继续计算会更容易一些,例如:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; tax = 12.5 / 100
&gt;&gt;&gt;价格= 100.50
&gt;&gt;&gt;价格*税
12.5625
&gt;&gt;&gt;价格+ _
113.0625
&gt;&gt;&gt;圆(_,2)
113.06
</code></pre>
<p>该变量应被用户视为只读. 不要为其显式赋值 - 您将创建一个具有相同名称的独立局部变量,以使用其魔术行为屏蔽内置变量. </p>
<p>除了<a href="#">int</a>和<a href="#">float</a>之外,Python还支持其他类型的数字,例如<a href="#">Decimal</a>和<a href="#">Fraction</a>.  Python还内置了对<a href="#">复数</a>的支持,并使用j或J后缀来表示虚部(例如<code>3 + 5j</code>). </p>
<h4 id="3.1.2.">3.1.2. 字符串</h4>
<p>除了数字,Python还可以操作字符串,这可以通过多种方式表达. 它们可以用单引号(<code>'''</code>)或双引号(<code>"..."</code>)括起来,结果相同[2].  \可用于转义引号:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;'垃圾邮件'#单引号
'垃圾鸡蛋'
&gt;&gt;&gt;'不'#用''逃避单引号......
"不"
&gt;&gt;&gt;"不"#...或使用双引号代替
"不"
&gt;&gt;&gt;'"是的,"他们说. 
"是的,"他们说. 
&gt;&gt;&gt;"\"是的,"他们说. "
"是的,"他们说. 
&gt;&gt;&gt;'"不是,"他们说. 
"不是吗,"他们说. 
</code></pre>
<p>在交互式解释器中,输出字符串用引号括起来,特殊字符用反斜杠转义. 虽然这有时可能与输入看起来不同(封闭的引号可能会改变),但这两个字符串是等价的. 如果字符串包含单引号而没有双引号,则该字符串用双引号括起来,否则用单引号括起来.  <a href="#">print()</a>函数通过省略括号引号和打印转义字符和特殊字符来产生更易读的输出:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;'"不是,"他们说. 
"不是吗,"他们说. 
&gt;&gt;&gt; print('"不是,"他们说. ')
"不是,"他们说. 
&gt;&gt;&gt; s ='第一行. \ n第二行. ' #\ n表示换行符
&gt;&gt;&gt; s#without print(),\ n包含在输出中
'第一行. \ n第二行. '
&gt;&gt;&gt; print(s)#with print(),\ n生成一个新行
第一行. 
第二行. 
</code></pre>
<p>如果你不希望以<code>\</code>开头的字符被解释为特殊字符,你可以通过在第一个引号之前添加一个r来使用原始字符串:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; print('C:\ some \ name')#here \ n表示换行!
C:\一些\名称
&gt;&gt;&gt; print(r'C:\ some \ name')#注意报价前的r
C:\一些\名称
</code></pre>
<p>字符串文字可以跨越多行. 一种方法是使用三引号:<code>"""......"""或</code>''''''''<code>`行尾自动包含在字符串中,但可以通过在行尾添加"\</code>来防止这种情况. 以下示例:</p>
<pre class='prettyprint'><code>打印("""\
用法:东西[OPTIONS]
&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;-h显示此用法消息
&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;-H hostname要连接的主机名
""")
</code></pre>
<p>产生以下输出(请注意,不包括初始换行符):</p>
<pre class='prettyprint'><code>用法:东西[OPTIONS]
&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;-h显示此用法消息
&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;-H hostname要连接的主机名
</code></pre>
<p>字符串可以与<code>+</code>运算符连接(粘合在一起),并用<code>*</code>重复:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;#3次'un',其次是'ium'
&gt;&gt;&gt; 3 *'un'+'ium'
'unununium'
</code></pre>
<p>两个或多个彼此相邻的字符串文字(即括号之间的字符串文字)会自动连接. </p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;'Py''thon'
'蟒蛇'
</code></pre>
<p>当您想要断开长字符串时,此功能特别有用:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; text =('在括号内放几个字符串'
......'让他们联合起来. ')
&gt;&gt;&gt;文字
"在括号内放几个字符串,让它们连在一起. "
</code></pre>
<p>这仅适用于两个文字,而不是变量或表达式:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; prefix ='Py'
&gt;&gt;&gt;前缀'thon'#不能连接变量和字符串文字
&amp;nbsp;&amp;nbsp;文件"&lt;stdin&gt;",第1行
&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;前缀'thon'
&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;^
SyntaxError:语法无效
&gt;&gt;&gt;('un'* 3)'ium'
&amp;nbsp;&amp;nbsp;文件"&lt;stdin&gt;",第1行
&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;('un'* 3)'ium'
&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;^
SyntaxError:语法无效
</code></pre>
<p>如果要连接变量或变量和文字,请使用<code>+</code>:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;前缀+'thon'
'蟒蛇'
</code></pre>
<p>字符串可以被索引(下标),第一个字符具有索引0.没有单独的字符类型;一个字符只是一个大小为1的字符串:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word ='Python'
&gt;&gt;&gt; word [0]#在0位置的字符
'P'
&gt;&gt;&gt;单词[5]位置5的#字符
'N'
</code></pre>
<p>指数也可能是负数,从右边开始计算:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word [-1]#last character
'N'
&gt;&gt;&gt; word [-2]#倒数第二个字符
'O'
&gt;&gt;&gt;单词[-6]
'P'
</code></pre>
<p>请注意,由于-0与0相同,因此负索引从-1开始. </p>
<p>除索引外,还支持切片. 虽然索引用于获取单个字符,但切片允许您获取子字符串:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word [0:2]#位置0(包括)到2的字符(不包括在内)
'PY'
&gt;&gt;&gt;单词[2:5]#位置2(包括)到5(排除在外)#字符
"寿"
</code></pre>
<p>请注意如何始终包含开始,并始终排除结束. 这确保了<code>s [:i] + s [i:]</code>总是等于<code>s</code>:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;单词[:2] +单词[2:]
'蟒蛇'
&gt;&gt;&gt;单词[:4] +单词[4:]
'蟒蛇'
</code></pre>
<p>切片索引具有有用的默认值;省略的第一个索引默认为零,省略的第二个索引默认为要切片的字符串的大小. </p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;单词[:2]#字符从开头到位置2(不包括)
'PY'
&gt;&gt;&gt;单词[4:]#位置4(包括)到最后的#字符
'上'
&gt;&gt;&gt; word [-2:]从倒数第二个(包括)到结尾的#个字符
'上'
</code></pre>
<p>记住切片如何工作的一种方法是将索引视为指向字符之间,第一个字符的左边缘编号为0.然后,n个字符的字符串的最后一个字符的右边缘具有索引n,例如:</p>
<pre class='prettyprint'><code>&amp;nbsp;+ --- + --- + --- + --- + --- + --- +
&amp;nbsp;| P | y | t | h | o | n |
&amp;nbsp;+ --- + --- + --- + --- + --- + --- +
&amp;nbsp;0 1 2 3 4 5 6
-6 -5 -4 -3 -2 -1
</code></pre>
<p>第一行数字给出了字符串中索引0 … 6的位置;第二行给出相应的负指数. 从i到j的切片分别由标记为i和j的边之间的所有字符组成. </p>
<p>对于非负索引,切片的长度是索引的差异,如果两者都在边界内. 例如,<code>word [1:3]</code>的长度为2. </p>
<p>尝试使用过大的索引将导致错误:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word [42]#这个词只有6个字符
Traceback(最近一次调用最后一次):
&amp;nbsp;&amp;nbsp;在&lt;module&gt;中的文件"&lt;stdin&gt;",第1行
IndexError:字符串索引超出范围
</code></pre>
<p>但是,在用于切片时,优雅地处理超出范围的切片索引:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;字[4:42]
'上'
&gt;&gt;&gt;单词[42:]
""
</code></pre>
<p>Python字符串无法更改 - 它们是<a href="#">immutable</a>. 因此,分配给字符串中的索引位置会导致错误:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; word [0] ='J'
Traceback(最近一次调用最后一次):
&amp;nbsp;&amp;nbsp;在&lt;module&gt;中的文件"&lt;stdin&gt;",第1行
TypeError:'str'对象不支持项目分配
&gt;&gt;&gt; word [2:] ='py'
Traceback(最近一次调用最后一次):
&amp;nbsp;&amp;nbsp;在&lt;module&gt;中的文件"&lt;stdin&gt;",第1行
TypeError:'str'对象不支持项目分配
</code></pre>
<p>如果您需要不同的字符串,则应创建一个新字符串:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;'J'+字[1:]
"Jython的
&gt;&gt;&gt; word [:2] +'py'
'Pypy'
</code></pre>
<p>内置函数<a href="#">len()</a>返回字符串的长度:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; s ='supercalifragilisticexpialidocious'
&gt;&gt;&gt; len(s)
34
</code></pre>
<p>也可以看看</p>
<p><a href="#">文本序列类型 -  str</a></p>
<p>字符串是序列类型的示例,并支持此类型支持的常见操作. </p>
<p><a href="#">字符串方法</a></p>
<p>字符串支持大量基本转换和搜索方法. </p>
<p><a href="#">格式化字符串文字</a></p>
<p>具有嵌入式表达式的字符串文字. </p>
<p><a href="#">格式字符串语法</a></p>
<p>有关str.format()的字符串格式的信息. </p>
<p><a href="#">printf-style String Formatting</a></p>
<p>当字符串是%运算符的左操作数时调用的旧格式化操作在此处更详细地描述. </p>
<h4 id="3.1.3.">3.1.3. 清单</h4>
<p>Python知道许多复合数据类型,用于将其他值组合在一起. 最通用的是列表,它可以写成方括号之间的逗号分隔值(项)列表. 列表可能包含不同类型的项目,但通常项目都具有相同的类型. </p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; square = [1,4,9,16,25]
&gt;&gt;&gt;正方形
[1,4,9,16,25]
</code></pre>
<p>像字符串(以及所有其他内置的<a href="#">sequence</a>类型),列表可以被索引和切片:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; square [0] #indexing返回项目
1
&gt;&gt;&gt;正方形[-1]
25
&gt;&gt;&gt;正方形[-3:]#切片返回一个新列表
[9,16,25]
</code></pre>
<p>所有切片操作都返回包含所请求元素的新列表. 这意味着以下切片返回列表的新(浅)副本:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;正方形[:]
[1,4,9,16,25]
</code></pre>
<p>列表还支持串联等操作:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;正方形+ [36,49,64,81,100]
[1,4,9,16,25,36,49,64,81,100]
</code></pre>
<p>与<a href="#">immutable</a>字符串不同,列表是<a href="#">mutable</a>类型,即可以更改其内容:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; cubes = [1,8,27,65,125]#这里的东西错了
&gt;&gt;&gt; 4 ** 3#4的立方体是64,而不是65!
64
&gt;&gt;&gt; cubes [3] = 64#替换错误的值
&gt;&gt;&gt;立方体
[1,8,27,64,125]
</code></pre>
<p>您还可以使用<a href="#">append()</a>方法在列表末尾添加新项目(我们稍后会看到有关方法的更多信息):</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; cubes.append(216)#添加6的立方体
&gt;&gt;&gt; cubes.append(7 ** 3)#和7的立方体
&gt;&gt;&gt;立方体
[1,8,27,64,125,216,343]
</code></pre>
<p>也可以分配切片,这甚至可以改变列表的大小或完全清除它:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; letters = ['a','b','c','d','e','f','g']
&gt;&gt;&gt;信件
['a','b','c','d','e','f','g']
&gt;&gt;&gt;#替换一些值
&gt;&gt;&gt;字母[2:5] = ['C','D','E']
&gt;&gt;&gt;信件
['a','b','C','D','E','f','g']
&gt;&gt;&gt;#现在删除它们
&gt;&gt;&gt;字母[2:5] = []
&gt;&gt;&gt;信件
['a','b','f','g']
&gt;&gt;&gt;#通过用空列表替换所有元素来清除列表
&gt;&gt;&gt;字母[:] = []
&gt;&gt;&gt;信件
[]
</code></pre>
<p>内置函数<a href="#">len()</a>也适用于列表:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; letters = ['a','b','c','d']
&gt;&gt;&gt; len(字母)
4
</code></pre>
<p>可以嵌套列表(创建包含其他列表的列表),例如:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; a = ['a','b','c']
&gt;&gt;&gt; n = [1,2,3]
&gt;&gt;&gt; x = [a,n]
&gt;&gt;&gt; x
[['a','b','c'],[1,2,3]]
&gt;&gt;&gt; x [0]
['a','b','c']
&gt;&gt;&gt; x [0] [1]
'B'
</code></pre>
<h3 id="3.2.">3.2. 迈向编程的第一步</h3>
<p>当然,我们可以将Python用于更复杂的任务,而不是将两个和两个一起添加. 例如,我们可以编写<a href="#">Fibonacci系列</a>的初始子序列,如下所示:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt;#斐波那契系列:
...#两个元素的总和定义下一个
...... a,b = 0,1
&gt;&gt;&gt;而&lt;10:
...打印(一)
...... a,b = b,a + b
...
0
1
1
2
3
五
8
</code></pre>
<p>此示例介绍了几个新功能. </p>
<ul>
<li><p>第一行包含多个赋值:变量<code>a</code>和<code>b</code>同时获取新值0和1.在最后一行再次使用它,证明右侧的表达式都是首先计算的在任何任务发生之前. 右侧表达式从左到右进行评估. </p></li>
<li><p>只要条件(这里:<code>a &lt;10</code>)保持为真,<a href="#">while</a>循环就会执行. 在Python中,就像在C中一样,任何非零整数值都是真的;零是假的. 条件也可以是字符串或列表值,实际上是任何序列;任何长度非零的东西都是真的,空序列都是假的. 该示例中使用的测试是简单的比较. 标准比较运算符的编写方式与C中相同:<code>&lt;</code>(小于),<code>&gt;</code>(大于),<code>==</code>(等于),<code>&lt;=</code>(小于或等于),<code>&gt; =</code>(大于或等于)和<code>!=</code>(不等于). </p></li>
<li><p>循环体是缩进的:缩进是Python对语句进行分组的方式. 在交互式提示符下,您必须为每个缩进行键入一个选项卡或空格. 在实践中,您将使用文本编辑器为Python准备更复杂的输入;所有体面的文本编辑都有自动缩进功能. 当以交互方式输入复合语句时,必须后跟一个空行以指示完成(因为解析器在您键入最后一行时无法猜出). 请注意,基本块中的每一行必须缩进相同的数量. </p></li>
<li><p><a href="#">print()</a>函数写入给定参数的值. 它与仅处理多个参数,浮点数量和字符串的方式不同,只是编写您想要编写的表达式(正如我们之前在计算器示例中所做的那样). 字符串打印时不带引号,并在项目之间插入空格,因此您可以很好地格式化事物,如下所示:</p></li>
</ul>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; i = 256 * 256
&gt;&gt;&gt; print('我的价值是',i)
i的值是65536
</code></pre>
<p>关键字参数end可用于避免输出后的换行符,或者使用不同的字符串结束输出:</p>
<pre class='prettyprint'><code>&gt;&gt;&gt;
&gt;&gt;&gt; a,b = 0,1
&gt;&gt;&gt;而&lt;1000:
... print(a,end =',')
...... a,b = b,a + b
...
0,1,1,2,3,5,8,13,21,34,55,89,144,233,377,610,987,
</code></pre>
<h2 id='脚注'>脚注</h2>
<p><a href="#">1</a>因为<code>**'的优先级高于</code>-<code>,所以</code>-3 ** 2<code>将被解释为</code> - (3 ** 2)<code>,从而得到</code>-9<code>. 要避免这种情况并得到</code>9<code>,你可以使用</code>( -  3)** 2`. </p>
<p><a href="#">2</a>与其他语言不同,诸如<code>\ n</code>之类的特殊字符与单引号(''''')和双引号(<code>"..."</code>)具有相同的含义. 两者之间的唯一区别是在单引号内你不需要逃避<code>``(但你必须逃避</code>\'`),反之亦然.</p>

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
    </div>
</div>

<script src="/lib/jquery-3.2.1.min.js"></script>
<script>
    /** 评论*/
    var url = "/php/forum/content_get.php?tag=python3.7.2&contentid=3";
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