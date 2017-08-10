<?php
namespace houdunwang\model;
use PDO;
use PDOException;
class Base{
    //保存表的名字  例如  arc
    private $table;
    //将pdo对象保存为静态私有属性，以便在类中任何地方都可使用该属性
    private static $pdo ;
    //保存where    代表的是SQL语句中  where条件  例如 ：aid=3;
    private $where;
    //保存数据表  主键的字段,并给个默认值
    private $priKey='aid';
    //将来接数据库的方法写在构造函数中，一旦new了Base这个类，就自动连接了数据库
    public function __construct($table){
        //当new Base类时  就会自动触发__construct,调用connect方法  实现了数据库的链接
        $this->connect();
        //将Model类（Model类触发了当前Base类）中获得的$table (代表的是哪个数据表 例如  arc )传参过来，并赋值给当前Base类的table属性
        $this->table = $table;
    }

    private function connect(){
        //为了防止重复执行连接数据库的方法，将pdo对象保存为静态属性，在多次调用该方法时，静态属性  值不会丢失，然后加以判断，只有静态属性$pdo  值为null时才执行连接数据库的方法
        //self::$pdo  调用当前类的静态属性$pdo
        if(is_null(self::$pdo)){
            try{
                //将链接数据库时的参数 都写成 调用c函数的形式，c函数引入了数据库配置文件batabase.php，只需改动配置文件中的值，此处就能实现改动链接数据库的参数
                $dsn = 'mysql:host=' . c('database,host') . ';dbname=' .c('database,db_name') ;
                $pdo = new PDO($dsn,c('database,db_user'),c('database,db_password'));
                //设置错误属性为异常错误
                $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $pdo->exec("SET NAMES " . c ('database,db_charset') );
                //将PDO对象赋值给静态属性中，这样$pdo的值就不为null了，当再次new Base这个类时，也不会重复执行连接数据库了
                self::$pdo = $pdo;
            }catch (PDOException $e){
                exit($e->getMessage());
            }
        }
    }


    public function get(){
        // 组合一条sql语句
        $sql = "SELECT * FROM {$this->table} ORDER BY aid  ";
        //此处的$pdo已经在连接数据库时被赋值了PDO对象了
        $result = self::$pdo->query($sql);
        //只要关联数据
        $data = $result->fetchAll(PDO::FETCH_ASSOC);
        // 该方法返回值 $data ，先是返回给 houdunwang\model\Model中的  call_user_func_array([new Base($table),$name]，接着再返给触发者这个__callstatic 函数的 也就是Model类的子类 Arc类，最终返给了 Entry.php 中的index方法中的 Arc::get();
        return $data;

    }


    public function save($post){
        //为了防止将一些  非 表字段的内容误保存到数据表中，例如  验证码，arc表中没有验证码这个字段，也就不需要要将验证码保存到数据库中去，所以需要过滤post提交的数据
        //不同的表结构不同，所以先查询一下当前表的信息（表结构）
        $tableInfo = $this->q("DESC {$this->table}");
        //p2($tableInfo);
//        Array
//        (
//            [0] => Array
//            (
//                [Field] => aid
//                [Type] => smallint(6)
//                [Null] => NO
//                [Key] => PRI
//                [Default] =>
//                [Extra] => auto_increment
//              )
//
//            [1] => Array
//                (
//                    [Field] => title
//                    [Type] => char(20)
//                    [Null] => NO
//                    [Key] =>
//                    [Default] =>
//                    [Extra] =>
//                )
//
//            [2] => Array
//                (
//                    [Field] => click
//                    [Type] => smallint(6)
//                    [Null] => NO
//                    [Key] =>
//                    [Default] => 0
//                    [Extra] =>
//                )
//
//        )

        // 通过上面的p函数打印结果  就可以得知 当前表有三个字段aid,title,click
        //声明一个空数组，将 字段追加到数组中，将来当post提交的数据的中的某个键名不在这个数组中，就说明该数据不需要保存到数据表中
        $tableFields = [];
        //循环遍历 上面的 获取表结构的数组
        foreach ($tableInfo as $v){
            //单独将里面的 [Field] => title 的 键名Field的键值拿出来追加到数组,该数组的值就是  当前表的所有  字段
            $tableFields[] = $v['Field'];
        }

        // post提交过来的数据是
//        p1($_POST);
//            Array
//            (
//                [title] => 今天周一
//                [click] => 123
//                [captcha] => P5W6P
//            )

        //声明一个数组，将过滤后的post提交数据  追加到数组中
        $filterData = [];
        //循环遍历post提交过来的数据，检验其键名是否在 当前表的字段 组成的 数组中，在，就说明这个键值是需要保存到数据表的，然后就把对应的键值，键名追加到数组
        foreach ($post as $k => $v){
            //如果属于当前表的字段，那么保留，否则就过滤
            if(in_array($k,$tableFields)){
                //这种写法是将  键值$k对应着键值$v一块追加到数组中
                $filterData[$k] = $v;
            }
        }

        //此时的数组就变成了
//        Array
//		  (
//			[title] => 今天周一,
//			[click] => 123,
//		)
        //为了方便组合sql语句语句  insert into arc （title，click   就是上面数组的键名 ）  values ('今天周一’，’123'   键值  ); 依然要整合这个数组

        //整合字段（在过滤后的数组{没有了主键iad,因为post提交时不用填写aid，没有了验证码}，获得所有键名）
        $field = array_keys($filterData);
//        p2($field);
//        Array
//        (
//            [0] => title
//            [1] => click
//        )
        // 数组转字符串函数implode，用逗号连接，结果就是title，click   刚好就是sql语句中需要的格式
        $field = implode(',',$field);
        //整合出符合sql语句格式的值
        $values = array_values($filterData);
        //我们需要的sql语句中使用的格式为：
        // '今天是周一','123'
        // 当我们用  “，”  连接数组转为的字符串时  变为
        //     今天是周一”，“123  与所需格式 刚好  前后差一个双引号  ，所以 我们认为给它补上
        $values = '"' . implode('","',$values)  . '"';
        //组合好sql语句
        $sql = "INSERT INTO {$this->table} ({$field}) VALUES ({$values})";
        // 执行无结果集的e方法
        return $this->e($sql);


    }

    public function where($where){
        //将调用where方法传参的值赋给当前类的where属性，以便在类中任意地方使用
        $this->where = $where;
        //为了entry类中能链式调用where方法与 destroy方法，此处要返回一个对象，只有对象才能链式调用下一个方法
        return $this;
    }

    //  destroy (破坏，销毁) 某条数据的方法
    public function destroy(){
        //执行删除数据的方法  前提是 在执行where方法时有传参，这样能更好的防止  误删整个表格
        if(!$this->where){
            //如果当前类的where属性值为空，就说明执行where方法时，未传参，那么就echo ‘删除数据必须有where条件’这句话，然后不再执行下面的代码
            exit('删除数据必须有where条件');
        }
        //组合删除某个表格某条数据的sql语句
        //$this->table在entry中  执行 Arc::where("aid={$_GET['aid']}")->destory(); 时， 把Arc传给了Arc 的父类houdunwnag\model\Model,Model类中将Arc转为小写 又传给了当前类Base
        $sql = "DELETE FROM {$this->table} WHERE {$this->where}";
        // 执行当前类的无结果集操作
        return $this->e($sql);
    }

    //封装一个方法用来获得当前表格 的主键，因为我们在查询某条数据、修改某条数据时，都会用到表格的主键字段

    private function getKey(){
        //查询一下表结构，哪个字段里有 [Key] => PRI  就说明其为主键
        //         [Field] => aid
//                [Type] => smallint(6)
//                [Null] => NO
//                [Key] => PRI
//                [Default] =>
//                [Extra] => auto_increment
    //组合一条查询表结构的语句
    $sql = "DESC {$this->table}";
        //执行当前类的有结果集的操作，返回一个数组
    $data = $this->q($sql);
        //循环遍历这个数组，看哪个单元含有[Key] => PRI
        foreach ($data as $v){
            if($v['Key'] == 'PRI'){
                //将这个单元的['Field']的值赋给变量$prikey;
                $priKey = $v['Field'];
                //当获得主键后就跳出循环
                break;
            }
        }
        //给函数一个返回值，就是获得的主键
        return $priKey;
    }
    //使用到上面封装好的获得主键的方法，再次封装一个 查询数据表某一条数据的方法，也是为实现 修改功能做准备
    public function find($id){
        //获得想要查询的表的主键
        $pri = $this->getKey();
        //组合查询一条数据的sql语句
        $sql = "SELECT * FROM {$this->table} WHERE {$pri}={$id}";
        //执行当前类中的有结果集的操作
        $data = $this->q($sql);
        //返回 查询的结果  的  当前单元，将二维数组变为了一维，方便以后使用
        return current($data);
    }
    //修改某条数据的方法
    public function update($data){
//        p2($data);
//        Array
//        (
//            [title] => 后盾网
//            [click] => 32767
//        )

        // 我们需要把上面的post数据  变为   title='后盾网'，click=‘32767’  放到sql语句中
        //当我们在Entry类调用执行完where方法后，在执行此处的update；执行where后并where的参数赋给Base类中的where属性，
      //当where 有了属性值后，再执行下面的修改语句，才不会出现把数据表全部改掉的危险
       // Arc::where("aid={$aid}")->update($_POST);
        if(!$this->where){
            exit('修改数据必须有where条件');
        }
        //声明一个变量，用来保存整合好的post提交的数据
        $set = '';
        // 我们需要把上面的post数据  变为   title='后盾网'，click=‘32767’  放到sql语句中
        foreach ( $data as $field => $value ) {
            //此处循环了两次， 第一次 实现了$aet = title='后盾网',  第二次之后    $set = title='后盾网'，click='32767',
            $set .= "{$field}='{$value}',";
        }
        //把右边多余的  逗号  去除
        $set = rtrim($set,',');
        //    修改数据的sql 语句格式 ：  update arc set title='明天有雨',click='100' where aid = 77;
        $sql = "UPDATE {$this->table} SET {$set} WHERE {$this->where}";
        //执行当前类的无结果集操作
        return $this->e($sql);
    }






    //封装的e方法  负责执行没有结果集的操作
    public function e($sql){
        self::$pdo->exec($sql);
    }

    //q方法 ，执行有结果集的操作

    public function q($sql){
        //捕获异常错误
        try{
            $result = self::$pdo->query($sql);
            //只要关联数据
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            exit("SQL错误 ：" . $e->getMessage());
        }



    }












}
?>