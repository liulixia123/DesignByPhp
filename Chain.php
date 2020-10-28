<?php
/**
 * 
 */
/**
 * 经过责任链的Request类
 *
 * 关于请求: 有时候，不需要一个请求对象，只需一个整型数据或者一个数组即可。
 * 但是作为一个完整示例，这里我们生成了一个请求类。
 * 在实际项目中，也推荐使用请求类，即是是一个标准类\stdClass，
 * 因为这样的话代码更具扩展性，因为责任链的处理器并不了解外部世界，
 * 如果某天你想要添加其它复杂处理时不使用请求类会很麻烦
 */
class Request
{
    // getter and setter but I don't want to generate too much noise in handlers
}
/**
 * 责任链的通用处理器类Handler（通常是一个接口或抽象类）
 *
 * 当然你可以通过一个更简单的处理器实现更加轻量级的责任链，
 * 但是如果你想让你的责任链拥有更好的扩展性和松耦合，
 * 那么就需要模拟一个更加真实的场景：通常一个责任链每时每刻都会被修改，
 * 这也是为什么我们在这里将其切分成好几个部分来完成。
 */
abstract class Handler
{
    /**
     * @var Handler
     */
    private $successor = null;

    /**
     * 追加处理类到责任链
     * 通过这个方法可以追加多个处理类到责任链
     *
     * @param Handler $handler
     */
    final public function append(Handler $handler)
    {
        if (is_null($this->successor)) {
            $this->successor = $handler;
        } else {
            $this->successor->append($handler);
        }
    }

    /**
     * 处理请求
     *
     * 这里我们使用模板方法模式以确保每个子类都不会忘记调用successor
     * 此外，返回的布尔值表明请求是否被处理
     * 
     * @param Request $req
     *
     * @return bool
     */
    final public function handle(Request $req)
    {
        $req->forDebugOnly = get_called_class();
        $processed = $this->processing($req);
        if (!$processed) {
            // the request has not been processed by this handler => see the next
            if (!is_null($this->successor)) {
                $processed = $this->successor->handle($req);
            }
        }

        return $processed;
    }

    /**
     * 每个处理器具体实现类都要实现这个方法对请求进行处理
     *
     * @param Request $req
     *
     * @return bool true if the request has been processed
     */
    abstract protected function processing(Request $req);
}
/**
 * 该类和FastStorage基本相同，但也有所不同
 *
 * 责任链模式的一个重要特性是: 责任链中的每个处理器都不知道自己在责任链中的位置，
 * 如果请求没有被处理，那么责任链也就不能被称作责任链，除非在请求到达的时候抛出异常
 *
 * 为了实现真正的扩展性，每一个处理器都不知道后面是否还有处理器
 *
 */
class SlowStorage extends Handler
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->data = $data;
    }

    protected function processing(Request $req)
    {
        if ('get' === $req->verb) {
            if (array_key_exists($req->key, $this->data)) {
                $req->response = $this->data[$req->key];
                return true;
            }
        }

        return false;
    }
}
/**
 * FastStorage类
 */
class FastStorage extends Handler
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->data = $data;
    }

    protected function processing(Request $req)
    {
        if ('get' === $req->verb) {
            if (array_key_exists($req->key, $this->data)) {
                $req->response = $this->data[$req->key];
                return true;
            }
        }

        return false;
    }
}
/**
 * ChainTest用于测试责任链模式
 */
class ChainTest
{

    /**
     * @var FastStorage
     */
    protected $chain;

    protected function setUp()
    {
        $this->chain = new FastStorage(array('bar' => 'baz'));
        $this->chain->append(new SlowStorage(array('bar' => 'baz', 'foo' => 'bar')));
    }

    public function makeRequest()
    {
        $request = new Request();
        $request->verb = 'get';

        return array(
            array($request)
        );
    }

    /**
     * @dataProvider makeRequest
     */
    public function testFastStorage($request)
    {
        $request->key = 'bar';
        $ret = $this->chain->handle($request);

        $this->assertTrue($ret);
        $this->assertObjectHasAttribute('response', $request);
        $this->assertEquals('baz', $request->response);
        // despite both handle owns the 'bar' key, the FastStorage is responding first
        $className = 'FastStorage';
        $this->assertEquals($className, $request->forDebugOnly);
    }

    /**
     * @dataProvider makeRequest
     */
    public function testSlowStorage($request)
    {
        $request->key = 'foo';
        $ret = $this->chain->handle($request);

        $this->assertTrue($ret);
        $this->assertObjectHasAttribute('response', $request);
        $this->assertEquals('bar', $request->response);
        // FastStorage has no 'foo' key, the SlowStorage is responding
        $className = 'DesignPatterns\Behavioral\ChainOfResponsibilities\Responsible\SlowStorage';
        $this->assertEquals($className, $request->forDebugOnly);
    }

    /**
     * @dataProvider makeRequest
     */
    public function testFailure($request)
    {
        $request->key = 'kurukuku';
        $ret = $this->chain->handle($request);

        $this->assertFalse($ret);
        // the last responsible :
        $className = 'DesignPatterns\Behavioral\ChainOfResponsibilities\Responsible\SlowStorage';
        $this->assertEquals($className, $request->forDebugOnly);
    }
}
$chainTest  = new ChainTest();
$chainTest::setUp();
$chainTest->testFastStorage($chainTest->makeRequest());