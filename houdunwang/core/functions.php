<?php
function p1($var){
    echo '<pre style="padding: 10px;background: red">';
    print_r($var);
    echo '</pre>';
}
function p2($var){
    echo '<pre style="padding: 10px;">';
    print_r($var);
    echo '</pre>';
}

//我们在函数库中封装一个c 方法，这个方法负责载入了 数据库配置文件（即：system/config/database.php,该文件return了一个数组，数组的每个单元就是数据库的一项配置,例如：'db_name'=> 'c83',到时候在PDO抽象层  连接数据库时就不用写死了，而且像链接另一个数据库时，只需要把配置文件 system/config/database.php中的值改一下就ok了）


//当我们在PDO抽象层那里链接数据库时，只需要调用这个c方法（调用时要传两个参数，前一个是代表要进入database数据库（配置文件config中不只有database.php，还有captcha.hph（验证码的配置文件，负责调换验证码的底色，长度...）等，），后一个是代表想读取该文件中的哪条数据），
//例如   c(database,db_name)   表示想从database.php中获得要连接的数据库的名字（当前是c83）
 function c($path){
    //连个参数是作为一个整体传过来的，同样要分割一下，方便单独使用;传参时是逗号隔开传过来的，那么转数组时，就以 ， 作为分割点
    $arr = explode(',',$path);
//     p2($arr);
    //转数组后  $arr = ['database','db_name'];
    //想使用 配置文件中内容，前体是把该文件载入
//     $config = include '../system/config/' . $arr[0] .'.php';
     $config = include '../system/config/' . $arr[0] . '.php';
        //给函数一个返回值， 例如：在PDO抽象层那里 调用c方法  c(database,db_name)时，配置文件中若有这个db_name属性值，就把这个值返给它，否则就为NULL;
    return isset($config[$arr[1]]) ? $config[$arr[1]] : NULL;
}
?>