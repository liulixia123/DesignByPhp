<?php
interface CommandInterface{
	public function execute();
}

class ConcreateCommand implements CommandInterface{
	protected $receiver;
	public function __construct(Receiver $receiver){
		$this->receiver = $receiver;
	}
	public function execute(){
		return $this->receiver->write("执行命令");
	}
}

class Receiver{
	public function write($str){
		echo $str;
	}
}
class Invoker
{
    /**
     * @var CommandInterface
     */
    protected $command;

    /**
     * 在调用者中我们通常可以找到这种订阅命令的方法
     *
     * @param CommandInterface $cmd
     */
    public function setCommand(CommandInterface $cmd)
    {
        $this->command = $cmd;
    }

    /**
     * 执行命令
     */
    public function run()
    {
        $this->command->execute();
    }
}
class CommandTest
{

    /**
     * @var Invoker
     */
    protected $invoker;

    /**
     * @var Receiver
     */
    protected $receiver;

    public function setUp()
    {
        $this->invoker = new Invoker();
        $this->receiver = new Receiver();
    }

    public function testInvocation()
    {
        $this->invoker->setCommand(new ConcreateCommand($this->receiver));
        $this->invoker->run();
    }
}

$client = new CommandTest();
$client->setUp();
$client->testInvocation();