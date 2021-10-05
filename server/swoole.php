<?php

use Swoole\Coroutine\Http\Server;
use function Swoole\Coroutine\run;

run(function () {
    $server = new Server('127.0.0.1', 9502, false);
    $server->set([
        'worker_num'    => 16,
        'daemonize'     => true,
        'max_request'   => 10000,
        'dispatch_mode' => 1,
    ]);
    define('APPLICATION_PATH', dirname(__DIR__));
    $application = new \Yaf\Application(implode([
        APPLICATION_PATH,
        DIRECTORY_SEPARATOR,
        'conf/application.ini'
    ]));
    $application->bootstrap()->run();
    
    $server->handle('/', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) use ($application) {
        ob_start();
        try{
            $swooleRequest = new SwooleRequest($request);
            $application->getDispatcher()->dispatch($swooleRequest);
        } catch (\Yaf\Exception $ex) {
            $response->end(\json_encode([
                'message'   => $ex->getMessage(),
                'code'      => $ex->getCode(),
                'file'      => $ex->getFile(),
                'line'      => $ex->getLine(),
            ]));
        }
        $content = ob_get_contents();
        ob_end_clean();
        $response->end($content);
    });
    $server->start();
});

class SwooleRequest extends \Yaf\Request\Http {
    
    /**
     *
     * @var \Swoole\Http\Request
     */
    public $_swoole_request = null;
    
    public function __construct(\Swoole\Http\Request $swoole_request) {
        $this->_swoole_request = $swoole_request;
        parent::__construct($this->getServer('request_uri'), '');
        $this->setActionName(implode([
            ucfirst($this->_swoole_request->getMethod()),
            parent::getActionName()
        ]));
    }
    
    public function get($name = null, $default = null) {
        $get = $this->_swoole_request->get;
        return is_null($name) ? $get : (isset($get[$name]) ? $get[$name] : $default);
    }
    
    public function getPost($name = null, $default = null) {
        $post = $this->_swoole_request->post;
        return is_null($name) ? $post : (isset($post[$name]) ? $post[$name] : $default);
    }
    
    public function getHeader($name = null, $default = null) {
        $header = $this->_swoole_request->header;
        return is_null($name) ? $header : (isset($header[$name]) ? $header[$name] : $default);
    }
    
    public function getServer($name = null, $default = null) {
        $server = $this->_swoole_request->server;
        return is_null($name) ? $server : (isset($server[$name]) ? $server[$name] : $default);
    }
    
    public function getCookie($name = null, $default = null) {
        $cookie = $this->_swoole_request->cookie;
        return is_null($name) ? $cookie : (isset($cookie[$name]) ? $cookie[$name] : $default);
    }
    
    public function getFiles($name = null, $default = null) {
        $files = $this->_swoole_request->files;
        return is_null($name) ? $files : (isset($files[$name]) ? $files[$name] : $default);
    }
    
    public function getContent() {
        return $this->_swoole_request->getContent();
    }
    
    public function getData() {
        return $this->_swoole_request->getData();
    }
    
    public function getMethod(): string {
        return $this->_swoole_request->getMethod();
    }
    
}

