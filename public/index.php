<?php
//引入composer文件，以便以后实现 所需文件的自动载入
include '../vendor/autoload.php';
//调用  框架启动类  中的index方法
//   反斜杠  命名空间（类名导入的写法，最前面的  反斜杠  代表‘根目录下’找houdunwnag）
\houdunwang\core\Boot::run();
?>