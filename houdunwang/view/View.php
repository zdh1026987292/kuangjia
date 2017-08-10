<?php
// 指定类view的命名空间

namespace houdunwang\view;
class View{
    //下面的方法是由  app\home\comtroller\controller.php中的index方法中 View::make()方法调用的，所以下面的return值会返回去
    public static function __callStatic($name, $arguments){
        //  View::make()->with(compact('arcData'));  在Entry类中静态调用View类的方法，view类中不存在，从这里又去触发了Base类
        return call_user_func_array([new Base,$name],[]);
    }
}
?>