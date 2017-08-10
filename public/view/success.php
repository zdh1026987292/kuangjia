<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="./static/bt/css/bootstrap.min.css">
</head>
<body>
<div class="jumbotron" style="text-align: center">
     <!-- 我们在触发__tostring方法载入这个“信息提示页面“之前，已经在sunccess方法和setRedirect方法中，设置  跳转路径$url和 提示信息$msg，所以此页面可以直接使用这些属性值-->
    <h1><?php echo $this->msg ?></h1>
    <p>
        点击按钮跳转<br>
        3秒之后将自动跳转到首页
    </p>

     <!--  使用role属性告诉辅助设备（如屏幕阅读器）这个元素所扮演的角色，属于WAI-ARIA（无障碍网页应用）例如点击的按钮，就是role="button"；会让这个元素可点击-->
    <a href="javascript:<?php echo $this->url ?>;" role="button" class="btn btn-success">点击跳转</a>
</div>
<script>
    //使用定时炸弹，5秒之后输出 url（跳转路径）
    setTimeout(function () {
        <?php echo $this->url ?>
    },3000);
</script>
</body>
</html>