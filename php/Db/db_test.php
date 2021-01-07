<?php
/**
 * 这个文件是 kyour\Db类的使用实例
 * @auther kyour@vip.qq.com Kyour
 * @date 2020-4
 */
 require_once "Db.php";

  //数据库连接参数
 $conf = [
 	'host'   => 'localhost',
 	'dbname' => 'dbname',
 	'port'   => 3306,
 	'user'   => 'username',
 	'pwd'    => 'password'
 ];

 //实例化对象
 $db = Db($conf);

 try{
     //查询user表id为384的数据 -单条记录查询
     $ret = $db->query("SELECT `uid`,`realname` FROM `jz_user` WHERE `uid` = ?;", [384])->fetch(PDO::FETCH_ASSOC);
     print_r($ret); //结果： Array ( [uid] => 384 [realname] => Kyour )
     echo "<br>";

     //查询user表id大于384的10条数据 -多条数据查询
     $ret = $db->query("SELECT `uid`,`realname` FROM `jz_user` WHERE `uid` > ? limit 10;", [384])->fetchAll(PDO::FETCH_ASSOC);
     print_r($ret); //结果： Array ( [0] => Array ( [uid] => 385 [realname] => Kyour ) [1] => Array ( [uid] => 386 [realname] => 刘志强 ) ...)
     echo "<br>";

     //更新user表id为384 的记录 realname字段改为 'Test'
     $ret = $db->query("UPDATE `jz_user` SET `realname` = ? WHERE `uid` = ?;", ['Test3',384])->rowCount();//rowCount为统计影响行数
     print_r($ret); //结果：1 （此处为修改的记录条数，如果未改变则为0）
     echo "<br>";

     //新增一条记录
     $ret = $db->query("INSERT INTO `jz_user` (`uid`, `realname`,`phone` ) VALUES ( ?, ?, ?);",['Test3',384,'15181174586'])->rowCount();
     print_r($ret); //结果：1 （此处为修改的记录条数，如果未改变则为0）
     echo "<br>";

     //增加、修改、删除 的用法相同，修改sql即可


     //预处理的另一个用法 - 通过名称绑定参数
     $sql = "SELECT `uid`,`realname` FROM `jz_user` WHERE `uid` = :uid;";
     $param = [
         'uid' => 384
     ];
     $ret = $db->bindQuery($sql, $param)->fetch(PDO::FETCH_ASSOC);
     print_r($ret); //结果如上第一条一样
     echo "<br>";

 }
 catch (Throwable $t){//PHP7 错误捕捉
     die("发生错误:".$t->getMessage());
 }
 catch(Exception $e){//PHP5 错误捕捉
     die("发生错误:".$e->getMessage());
 }
