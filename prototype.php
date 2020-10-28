<?php
abstract class Prototype{
	protected $name;
	protected $title;
	public function __construct($name){
		$this->name = $name;
	}
	public function getTitle(){
		return $this->title;
	}
	public function setTitle($title){
		$this->title = $title;
	}
	//子类必须实现 abstract
	abstract public function copy();
}

class ConcretePrototype extends Prototype{
	protected $category = 'Bar';
	/**
     * 创建当前对象的浅表副本。方法是创建一个新对象，然后将当前对象的
     * 非静态字段复制到该新对象。如果字段是值类型的，则对该字段执行逐
     * 位复制。如果字段是引用类型，则复制引用但不复制引用的对象：因此。
     * 原始对象及其副本引用同一对象
     */
	public function copy(){
		return clone $this;
	}
}


class Client {
	public static function init(){
		$ConcretePrototype = new ConcretePrototype('client ');
		$p1 = $ConcretePrototype->copy();
		$p2 = $ConcretePrototype->copy();
		$p1->setTitle('P1 name');
		$p2->setTitle("P2 name");
		var_dump($ConcretePrototype);
		var_dump($p1);
		var_dump($p2);
	}
}

Client::init();