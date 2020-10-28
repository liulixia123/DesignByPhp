<?php
/**
 * 门面类
 */
class Facade
{
    /**
     * @var OsInterface
     */
    protected $os;

    /**
     * @var BiosInterface
     */
    protected $bios;

    /**
     * This is the perfect time to use a dependency injection container
     * to create an instance of this class
     *
     * @param BiosInterface $bios
     * @param OsInterface   $os
     */
    public function __construct(BiosInterface $bios, OsInterface $os)
    {
        $this->bios = $bios;
        $this->os = $os;
    }

    /**
     * turn on the system
     */
    public function turnOn()
    {
        $this->bios->execute();
        $this->bios->waitForKeyPress();
        $this->bios->launch($this->os);
    }

    /**
     * turn off the system
     */
    public function turnOff()
    {
        $this->os->halt();
        $this->bios->powerDown();
    }
}
/**
 * OsInterface接口
 */
interface OsInterface
{
    /**
     * halt the OS
     */
    public function halt();
}
/**
 * BiosInterface接口
 */
interface BiosInterface
{
    /**
     * execute the BIOS
     */
    public function execute();

    /**
     * wait for halt
     */
    public function waitForKeyPress();

    /**
     * launches the OS
     *
     * @param OsInterface $os
     */
    public function launch(OsInterface $os);

    /**
     * power down BIOS
     */
    public function powerDown();
}
class os implements OsInterface{
    public function halt(){
        return "加盐";
    }
}
class Bios implements BiosInterface{
    public function execute(){
        return "执行";
    }
    public function waitForKeyPress(){
        return "等待";
    }
    public function launch(OsInterface $os){
        return "依赖os";
    }
    public function powerDown(){

    }
}
class client{
    static public function index(){
        $facade = new Facade(new Bios(),new os());
        $facade->turnOff();
    }
}

var_dump(client::index());