<?php
class Singletion{
	private static $instance = NULL;
	private  function __construct(){}
	private  function __clone(){}

	public static function getInstance(){
		if(self::$instance===null){
			self::$instance = new self;
		}
		return self::$instance;
	}

}

$single = Singletion::getInstance();
var_dump($single);