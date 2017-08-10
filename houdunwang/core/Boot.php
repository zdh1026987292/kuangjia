<?php
//指定框架启动类Boot的命名空间
namespace houdunwang\core;

class Boot{
    //封装静态方法run，一切运行都是从单入口中 静态调用这个方法开始的
    public static function run(){
        //初始化框架
        self::init();
        //执行应用
        self::appRun();
    }

    public static function appRun(){
        //页面的切换依然是通过啊a链接的地址栏传参，但不在使用以前分别用c（控制器）和a（方法）传参的方式了，而是 ？s==home/entry/index   同样home是代表 走  哪个应用文件夹（网站分前台、后台），home一般是前台，admin一般是代表后台；c依然还是代表 走  哪个控制器 ；index  是代表 方法
        //参数不存在时，给默认值，显示出默认主页面
        $s = isset($_GET['s']) ? $_GET['s'] : 'home/entry/index';
        //三个参数连在一起，不方便单个使用，通过explode函数将字符串转为数组
        $arr = explode('/',$s);
//        p1($arr);
        /*Array(
            [0] => home
            [1] => entry
            [2] => index
        )*/
        // 把数组的说那个值 都定义为常量：常量在任何地方都能使用，不受全局和局部的限制
        //在后面houdunwang/vivw/Base.php中的make方法中组合模板路径（以便载入模板）时，需要用到a标签传参的这三个值
        //下面就是houdunwang/vivw/Base.php中组合模板路径时，用到的这几个常量
        // $this->template = '../app/' . APP . '/view/' . CONTROLLER . '/' . ACTION . '.php';
        //默认时，模板路径为     app     home    view      entry              index.php
        //APP 的值决定 引入的前台home文件中
        define('APP',$arr[0]);
        //CONTROLLER 决定 进入那个控制器
        define('CONTROLLER',$arr[1]);
        //ACTION 决定执行那个方法，不同的方法执行不同的功能，载入不同的模板
        define('ACTION',$arr[2]);


        //组合类名（这是类名导入的写法: 反斜杠！！，把将要实例化的类 的完整路径组合好）
       //  例如:  app\home\controller\controller (这个类不存在，composer就会自动把与这个类同路径的同名文件引入到当前页，)
       //   其中  home  和   controller  的值  是通过a传参来决定的，所以不能写死了
        $className = "\app\\{$arr[0]}\controller\\" .ucfirst($arr[1]);
        //调用控制器里面的方法
        echo call_user_func_array([new $className ,$arr[2]],[]);
        //上面调用方法的最终返回值为 对象， echo 对象时  就会触发 魔术方法__tostring,在此方法中载入了模板
    }

    //初始化
    public static function init(){
        //开启session（短路写法，当session_id不存在时，执行右边的代码）
        session_id() || session_start();
         //设置时区
        date_default_timezone_set('PRC');
        //定义一个     用来判断是否是POST提交方式     的常量
        define('IS_POST',$_SERVER['REQUEST_METHOD'] == 'POST' ? true : false);

    }
}
?>