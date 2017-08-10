<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>修改文章表</title>
    <link rel="stylesheet" href="./static/bt/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <form action="" method="post" class="form-horizontal" role="form" style="width:400px; margin:0 auto;">

        <div class="form-group">
            标题：
            <input name="title" id=""  class="form-control" value="<?php echo $oldData['title'] ?>">
        </div>
        <div class="form-group">
            点击数：
            <input name="click" id=""  class="form-control" value="<?php echo $oldData['click'] ?>">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success">确认修改</button>
            <a href="index.php" class="btn btn-success">返回首页</a>
        </div>
    </form>
</div>
</body>
</html>