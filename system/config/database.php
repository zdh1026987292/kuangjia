<?php
return [
    //当我们在PDO抽象层那里链接数据库时，一些参数不能写死，所以把这些参数做成配置文件，然后调用c 函数（c 函数写在了）核心目录core中
       //   调用 ：c(database,db_name);  此时c 函数的返回值就是c83


     //连接的主机名

       'db_host'=> '127.0.0.1',
    // 用户名
       'db_user'=> 'root',
    //密码
       'db_password'=> 'root',
    //数据库名字
       'db_name'=> 'c83',
    //字符集
       'db_charset'=> 'UTF8',
];





?>