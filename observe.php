<?php
abstract class Observe{
	abstract protected function update();
}
class concreteObserveA extends Observe{
	public function update(){
		echo  "更新通知";
	}
}
class concreteObserveB extends Observe{
	public function update(){
		echo  "短信通知";
	}
}
abstract class Subject{	
	abstract protected function attach(Observe $concreteObserve);
	abstract protected function detach(Observe $concreteObserve);
	abstract protected function notify();

}

class concreteSubject extends Subject{
	protected $observes = [];
	public function attach(Observe $concreteObserve){
		$this->observes[] = $concreteObserve;
	}
	public function detach(Observe $concreteObserve){
		$temparr = array_flip($this->observes);
		unset($temparr[$concreteObserve]);
		$this->observes = array_flip($temparr);
	}
	public function notify(){
		foreach ($this->observes as $key => $observe) {
			$observe->update();
		}
	}
}

/**
*  
*/
class client
{
	static public function init(){
		$subjectA = new concreteSubject();
		$ObserveA = new concreteObserveA();
		$ObserveB = new concreteObserveB();
		$subjectA->attach($ObserveA);
		$subjectA->attach($ObserveB);
		$subjectA->notify();
	}
}
client::init();
