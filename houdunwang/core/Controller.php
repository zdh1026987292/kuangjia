<?php
namespace houdunwang\core;
class Controller{
    // $url的值   是默认的   当添加、删除、编辑成功后 跳转的路径
  //跳转地址 给默认的是 返回上一页面
    private $url = "window.history.back()";
    //  $template是负责引入 “信息提示”模板的路径
    private $template ;
    // $meg 是提示信息  例如：添加成功
    private $msg;


    //负责跳转的方法
    //在调用此方法时，若传参 $url,就按传参的路径跳转，否则，就按$url的默认值跳转
    protected function setRedirect($url){
        $this->url = "location.href = '{$url}'";
        //下面的return $this ,目的和 开始时载入 默认页面时一样，为了链式调用下一个函数，最终  echo 对象，触发__toString,实现信息提示页面的载入
        return $this;
    }

    //（添加、删除、编辑..）成功的时候执行的方法
    //同样调用此方法时会传参$msg   例如：添加成功、删除成功
    protected function success($msg){
        //将传参过来的$msg的内容  赋值给 当前类的$msg属性
        $this->msg = $msg;
        //载入提示成功信息的页面
        //单入口点站点，访问的永远是public下的index.php页面，而提示成功信息的seccess.php页面就在public/view 下
        $this->template = "./view/success.php";
        //同样返回一个对象，先将这个对象返回给 controller子级类中add 方法，再紧接着返给 框架启动类类的appRun方法，在那里echo  这个$this对象时，触发了下面的__toString方法，于是就引入了 “成功信息提示页面”
        return $this;
    }

    protected function error($msg){
        //将传参过来的$msg的内容  赋值给 当前类的$msg属性
        $this->msg = $msg;
        //载入提示   失败  的 信息的页面
        //单入口点站点，访问的永远是public下的index.php页面，而提示 错误（例如验证码错误）信息的error.php页面就在public/view 下
        $this->template = "./view/error.php";
        //同样返回一个对象，先将这个对象返回给 controller子级类中index方法，再紧接着返给 框架启动类类的appRun方法，在那里echo  这个$this对象时，触发了下面的__toString方法，于是就引入了 “错误信息提示页面”
        return $this;
    }


    //当echo 一个对象时就会触发这个方法，这个方法负责载入模板，之所以要把载入模板放在此方法中，就是为了执行完上面的success（获得传参过来的提示信息）和setRedirect（获得传参过来的跳转路径）方法后，在载入模板，这样在模板中就能使用这些参数值
    public function __toString(){
        //载入页面跳转前的  信息提示  模板
        include $this->template;
        //__tostring  必须返回一个字符串（规定的），不然会报错
        return '';
    }

}
?>