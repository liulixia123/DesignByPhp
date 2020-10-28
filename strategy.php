<?php
interface UserStrategy{
	public function ad();
	public function product();
}

class MaleUserStrategy implements  UserStrategy{
	public function ad(){
		echo "展示男装广告<br>";
	}
	public function product(){
		echo "展示电子商品<br>";
	}
}

class FemaleUserStrategy implements  UserStrategy{
	public function ad(){
		echo "展示女装广告<br>";
	}
	public function product(){
		echo "展示女装商品<br>";
	}
}

class Strategy{
	//存放策略对象
	public $strategy;
	//设置策略对象
	public function setStrategy($strategy){
		$this->strategy = $strategy;
	}
	//定义展示广告和商品的方法
	public function index(){
		$this->strategy->ad();
		$this->strategy->product();	

	}
}

$Strategy = new Strategy();
$female = '';
if($female == 'female'){
	$people = new FemaleUserStrategy();
}else{
	$people = new MaleUserStrategy();
}
//把对象传入到策略类里
$Strategy->setStrategy($people);
//显示广告和类别－－因为使用了策略模式，当我们需要新增加一个策略的时候这里就不需要修改了
$Strategy->index();