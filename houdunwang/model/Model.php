<?php
namespace houdunwang\model;
use houdunwang\model\Base;
class Model{
    public static function __callStatic($name,$canshu){
       //get_called_class();  获取当前主调类的类名,  当我们执行Arc::get() 时，此处可以获取这个类名Arc,其格式为system\model\Arc

        $className = get_called_class();
//        p2($className);
        //   system\model\Arc

        //我们要从$className 中单拿出Arc（小写的），并将此作为参数传到 Base类中去，这样在Base类中 使用到的表明  就以此参数代替，这样操作的表就是随时可以更换的，而非写死的了
        //  strrchr($className,'\\')   从右侧的第一个  反斜杠 截取， 结果为  \Arc
        //  ltrim(strrchr($className,'\\'),'\\')  去除左侧的反斜杠  结果为   Arc
        //   strtolower  转为小写   就得到了 arc
        $table = strtolower(ltrim(strrchr($className,'\\'),'\\'));

        //
        return call_user_func_array([new Base($table),$name],$canshu);
    }
}
?>