<?php
session_start();
?>

<form action="note_new_summit.php" method="post" id="note_new">
    <div class="form-group">
        <label for="note_tag">标签:</label>
        <input type="text" readonly class="form-control-plaintext" id="note_tag" name="note_tag" value="标签">
    </div>
    <div class="form-group">
        <label for="title">标题:</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="请输入标题">
    </div>
    <div class="form-group">
        <label for="content">内容:</label>
        <textarea class="form-control" id="content" name="content" rows="5" placeholder="请输入内容"></textarea>
    </div>
    <?php
    if (isset($_SESSION['figure_url'])) {
        echo '<button type="submit" class="btn btn-primary ml-auto">提交</button>';
    } else {
        echo '<label class="btn btn-warning ml-auto">登录后方可提交</label>';
    }
    ?>
</form>

<script>
    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return decodeURI(r[2]);
        return null;
    }

    var tag = getUrlParam('tag');
    $('#note_tag').attr('value', tag);
</script>
<script>
    $("#note_new").submit(function () {
        var tag = $("#note_tag").attr('value');
        var title = $("#title").val();
        var content = $("#content").val();
        /** 这里是提交表单前的非空校验*/
        if (tag === "" || !tag) {
            alert("请选择标签");
            return false;/*阻止表单提交*/
        }

        if (title === "" || !title) {
            alert("请输入标题");
            return false;/*阻止表单提交*/
        }

        if (content === "" || !content) {
            alert("请输入内容");
            return false;/*阻止表单提交*/
        }

        return true;
    });
</script>