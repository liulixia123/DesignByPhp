<?php
class Target{
	public function request(){
		echo "普通请求";
	}
}

class Adeptee{
	public function specificRequest(){
		echo "特殊请求";
	}
}

class Adepter extends Target{
	/**
     * 创建一个私有的adaptee对象
     * @var Adaptee
     */
	private $adaptee;

	public function __construct(){
		$this->adaptee = new Adeptee();
	}

	public function request(){
		return $this->adaptee->specificRequest();
	}
}

class client{
	static public function init(){
		$Adepter = new Adepter();
		$Adepter->request();
	}
}
client::init();