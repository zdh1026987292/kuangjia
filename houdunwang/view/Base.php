<?php
//指定Base类的命名空间
namespace houdunwang\view;
class Base{
    //声明一个空数组变量，用来  保存 传参过来的数组
    private $data = [];
    //声明一个变量  用来保存  载入模板的路径
    private $template;
    public function make(){
        //组合一下载入模板的路径，这就是为甚要在框架启动类中要用get传参声明成三个常量，常量不受局限，在任何地方都可以使用
       $this->template = "../app/". APP. "/view/".CONTROLLER ."/" .ACTION .".php";
//        $this->template = '../app/' . APP . '/view/' . CONTROLLER . '/' . ACTION . '.php';
       //给函数一个返回值  $this (是个对象)   而make（）方法是在home/controller/Entry类中调用的，所以这个函数返回值（也就是$this对象）会直到返给 View::make()->with(compact('data')) 中 make（），此时make（）的值就是一个对象了，既然是对象  就能调用方法，所以又链式调用了后面的with（）方法。。
        return $this;
    }
    //封装一个with方法，此方法接收了在控制器Entry类中执行with方法的传参
    public function with($data){
        //将传参过来的变量值赋给当前的私有属性data，这样在类中任何地方都可以使用了
        $this->data = $data;
        //返回一个对象给 控制器Entry类中执行的with，最终目的还是为了echo 对象，触发这里的__tostring方法，实现先获得变量，后载入模板
        return $this;
        //with方法依然是返回一个对象，并且依然返回到 app/home/controller/controller.php文件中index方法中的        {return View::make()->with(compact('data'));} ，而其返回给触发这个index方法的 框架启动类 Boot中的appRun方法中，echo call_user_func_array([new $className ,$s[2]],[]); 此时 其就是 echo了一个对象，当echo 对象时  就会触发魔术方法__tostring,然后我们在此魔术方法中实现载入模板，此时传参过来的数组变量已经存在，在模板中可以遍历使用，综合起来实现了，先引变量，后载模板
    }

    public function __toString(){
        //ectract 提取的意思；与 compact（将变量名作为键名，变量值作为键值，方便传参），是将 键名 作为 变量名，键值  变为 变量值，方便使用   相当于变回之前的 $data = ['title'=>'明天有雨'];
        extract($this->data);
        // 载入模板
        include $this->template;
        // 魔术方法__tostring 必须return 一个字符串（可以是空字符串），否则会报错；
        return '';
    }
}
?>