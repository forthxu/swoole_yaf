<?php

/**
 * @name Bootstrap
 * @author {&$AUTHOR&}
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:\Yaf\Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends \Yaf\Bootstrap_Abstract {

    public function _initConfig() {
        //把配置保存起来
        $arrConfig = \Yaf\Application::app()->getConfig();
        \Yaf\Registry::set('config', $arrConfig);
    }
    
    public function _initLoader(\Yaf\Dispatcher $dispatcher) {
        \Yaf\Loader::getInstance()->registerLocalNamespace('Yapsr', APPLICATION_PATH . '/yapsr');
    }

    public function _initPlugin(\Yaf\Dispatcher $dispatcher) {
        //注册一个插件
        $objSamplePlugin = new SamplePlugin();
        $dispatcher->registerPlugin($objSamplePlugin);
    }

    public function _initRoute(\Yaf\Dispatcher $dispatcher) {
        //$router = $dispatcher->getRouter();
        //$router->addRoute('yapsr', (new Yapsr\Route()));
    }

    public function _initView(\Yaf\Dispatcher $dispatcher) {
        
    }

}
