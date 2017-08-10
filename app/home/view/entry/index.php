<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>这里是主页</title>
    <link rel="stylesheet" href="./static/bt/css/bootstrap.min.css">
</head>
<body style="background: #eeeeee;">
<div class="container" style="width: 600px; margin-top: 50px;">
    <table class="table table-hover table-bordered">
        <thead class="text-center">
        <tr class="success">
            <th class="text-center" style="width:15%;">编号</th>
            <th class="text-center">标题</th>
            <th class="text-center">点击数</th>
            <th class="text-center" style="width:20%;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($arcData as $k =>$v) : ?>
            <tr>
                <td class="text-center"><?php echo $k+1; ?> </td>
                <td><?php echo $v['title'] ?> </td>
                <td class="text-center"><?php echo $v['click'] ?> </td>
                <td class="text-center">
                    <a href="?s=home/entry/update&aid=<?php echo $v['aid']  ?>" class="btn btn-primary btn-xs">编辑</a>
                    <!-- 在a标签中加上confirm，防止用户误操作删除数据-->
                    <!-- 当加上javasecipt的确认弹窗后，后面的s传参必须加上location.href='' -->
                    <a href="javascript:if(confirm('确定要删除吗？')) location.href='?s=home/entry/remove&aid=<?php echo $v['aid'] ?>;'" class="btn btn-danger btn-xs">删除</a>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <hr>
    <form action="" method="post" class="form-horizontal" role="form" style="width: 300px; margin: 0 auto;">
        <div class="form-group">
            标题：
            <input type="text" name="title" class="form-control" required="required">
        </div>
        <div class="form-group">
            点击数：
            <input name="click" id=""  class="form-control" required="required">
        </div>
        <div class="form-group">

            <div class="col-sm-12">
                验证码: <input type="text" name="captcha" id="inputID" class="form-control" value="" title="" style="width: 100px;" required="required">
                <br>
                <img src="?s=home/entry/captcha" onclick="this.src=this.src+'&='+ Math.random()"    alt="">
                <span>点击图片换一张</span>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">确认添加</button>
        </div>
    </form>
</div>
</body>
</html>