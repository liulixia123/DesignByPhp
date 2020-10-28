<?php
abstract class Operation{
	protected $numA;
	protected $numB;
	public function getNumA(){
		return $this->numA;
	}
	public function getNumB(){
		return $this->numB;
	}
	public function setNumA($numA){
		$this->numA = $numA;
	}
	public function setNumB($numB){
		$this->numB = $numB;
	}

	abstract protected function result();
}

class operationAdd extends Operation{
	public function result(){
		return $this->numA + $this->numB;
	}
}

class operationdel extends Operation{
	public function result(){
		return $this->numA - $this->numB;
	}
}

class operateFacatory{
	public static function init( $operate ){
		switch ($operate) {
			case '+':
				return new operationAdd();
				break;
			case '-':
				return new operationdel();
				break;
			
			default:
				# code...
				break;
		}
	}
}

class client{
	static public function index(){
		$strOperate = "-";
		$numA = 1;
		$numB = 1;
		$obj = operateFacatory::init($strOperate);
		$obj->setNumA( $numA );
        $obj->setNumB( $numB );
        $data = $obj->result();
        echo "结果是".$data;
	}
}

client::index();