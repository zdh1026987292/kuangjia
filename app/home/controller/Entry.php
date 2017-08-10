<?php
//指定当前类 Entry的命名空间
namespace app\home\controller;
//将本页面使用到的View类的 类名导入
use houdunwang\view\View;
//将Entry的父类Contriller 的类名导入
use houdunwang\core\Controller;
//将Arc的类名导入
use system\model\Arc;
//下面的类名导入  是加载的第三方库  验证码的 固定写法
use Gregwar\Captcha\CaptchaBuilder;
class Entry extends Controller{
    //进入单入口时，默认执行的就是这个index方法，这个方法负责引入主页面
    public function index(){

     // 在载入首页模板之前先获得数据库中  表arc中的所有数据   Arc::get();
        $arcData = Arc::get();

    // 若数据库 有arc表、tag表  那么我们就在  框架系统目录system下的 扩展模型中创建两个和    表的名字  相同的文件Arc.php和Tag.php文件，内分别有 Arc类  和   Tag类  ，是为扩展模型类，Arc类操作arc表格的增删改查

        //确定是post提交 也就是用户点击了添加按钮，再执行if里面代码体
        if(IS_POST){
            //首先判断验证码是否正确
            //$_SESSION['phrase'] 是我们调用的第三方库的写法  storing the phrase in the session to test for the user
            if(strtolower($_POST['captcha']) != strtolower($_SESSION['phrase'])){
                //如果验证码输入错误，则调用父类的error方法，这个errror方法组合了载入“提示（验证码）错误的页面”，返回一个$this对象到这里，接着又返回给框架启动类中，在Boot类中echo  这个返回值对象时，触发了Entry父类Controller中的__tostring方法，实现来了载入提示页面
                return $this->error('验证码错误')->setRedirect('index.php');
            }else{
                //当验证码正确的时候
                //顾名思义就是  将post提交的数据  保存到 arc 表中
                //所以我们在Base类中封装了save方法，此处只是调用封装好的方法，实现业务逻辑与数据源隔离，面向对象思想
                Arc::save($_POST);
                //链式调用父类CONTROLLER里面的success方法并返回一个对象，继续调用setRedirect方法，并将跳转路径传参过去，最后还是返回一个对象，这里又将结果返回到view类中，最终返给框架启动类中，当最后echo 这个返回结果对象时。触发了父类Controller中的__tostring方法，载入了成功提示页面
                return $this->success('添加成功')->setRedirect('index.php');
            }

        }
        //通过链式调用houdunwang\view\view的make方法和with方法，view类中不存在这两个方法，触发了Base类，执行完这两个方法之后，通过echo 其返回值 $this 对象，触发了Base类的__tostring函数，实现载入了默认首页面模板。并且是先传参数后载模板
        return View::make()->with(compact('arcData'));

    }
    //删除数据的方法，当用户点击首页的”删除“a标签时，传参？s=home/entry/remove,然后触发这个remove，同理依然把在这个方法要执行的删除数据库的sql语句及方法封装到模型目录的Base类中
    public function remove(){
        //假设要删除aid=3的数据
        //静态调用Arc类中的where，并传参aid=3，Arc类并无此方法，触发其父类houdunwang\model\Model中的__callstatic,并获得主调类名Arc，Model类又new Base类，带着参数arc（传给了Base类中的构造方法），去Base类中执行where（传参aid=3）方法
        Arc::where("aid={$_GET['aid']}")->destroy();
        return $this->success('删除成功')->setRedirect('index.php');
    }

    //在获得主键方法和 查询某条数据的基础上才有了这个update方法
    public function update(){
        //先获得get传参里面的 代表的是  点击要操作的是哪条数据的aid
        $aid = $_GET['aid'];
        //当post提交后执行的代码
        if(IS_POST){
            //静态调用Arc类中的where方法，并传参aid=3（假设），Arc类并无此方法，触发其父类houdunwang\model\Model中的__callstatic,并获得主调类名Arc，Model类又new Base类，带着参数arc（传给了Base类中的构造方法），去Base类中执行where（传参aid=3）方法，执行完where方法，得到的返回值是对象，同样又会调用update方法，实现了数据的修改
            Arc::where("aid={$aid}")->update($_POST);
            //载入修改成功提示页面
            return $this->success('修改成功')->setRedirect('index.php');
        }


        //当点击‘修改’a标签时，触发执行这个update方法，首先是通过静态调用Arc类中的find的方法，最终是执行了Base类中的find方法，获得了要修改的这条数据的内容，赋给变量$oldDate,
        $oldData = Arc::find($aid);
        //通过链式调用houdunwang\view\view（其实是Base里面）的make方法和with方法，载入了修改模板页面
        return View::make()->with(compact('oldData'));
    }


    //  生成验证码的方法： 使用时直接把调用此方法的参数写在img的标签中即可 <img src="?s=home/entry/captcha">
    public function captcha(){
        header('Content-type: image/jpeg');
        $builder = new CaptchaBuilder();
        $builder->build();
        $builder->output();
        //把生成的验证码的值存入到session中，以便用户提交数据时  比对验证码是否正确，session已经框架启动类中开启了
        $_SESSION['phrase'] = $builder->getPhrase();
    }








}
?>