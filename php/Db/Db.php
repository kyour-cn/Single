<?php declare(strict_types=1);
/**
 * PDO_MYSQL 数据库基础功能封装
 * @date 2018-6-12
 * @auther kyour@vip.qq.com Kyour
 */

class Db
{
	public $status = false; //连接状态
	public $pdo; //pdo对象

	/*
	 构造函数 -在实例化(new)的时候调用的
	 在这里主要做一些初始化操作 -连接数据库
	 @param $conf 传入的数据库参数，用于连接
	*/
	public function __construct(array $conf =[])
	{
		if(!$conf){
			throw new Exception("检测到未传入数据库连接配置");//抛出异常
		}
		//准备连接字符串
		$dsn = 'mysql:host='.$conf["host"].';dbname='.$conf["dbname"].';port='.$conf["port"];
		try{
			$pdo = new PDO($dsn, $conf["user"], $conf["pwd"]); //实例化PDO
			$pdo->query("SET NAMES ".$conf['coding']??'utf8'); //设置编码
			$this->pdo = $pdo; //保存到对象
			$this->status = true;
		}catch(\PDOException $e){
			die("数据库连接失败:".$e->getMessage());
		}
	}

	/*
	 执行sql语句
	 @param $sql 传入SQL语句
	 @param $param 传入预处理保留参数(替代sql中的?)
	*/
	public function query(string $sql, array $param = [])
	{
        if(!$this->status){
            return false;
        }
		$ps = $this->pdo->prepare($sql); //返回PDOStatement 对象
		$ps->execute($param);
		return $ps;
	}

	/*
	 执行sql语句 - 通过名称绑定预处理语句
	 @param $sql 传入SQL语句
	 @param $param 传入预处理保留参数(替代sql中的?)
	*/
	public function bindQuery(string $sql, array $param = [])
	{
        if(!$this->status){
            return false;
        }
		$ps = $this->pdo->prepare($sql); //返回PDOStatement 对象
		foreach ($param as $k => $v) {
            $ps->bindParam(':'.$k, $v);
		}
		$ps->execute();
		return $ps;
	}
}
