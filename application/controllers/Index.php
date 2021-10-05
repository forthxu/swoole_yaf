<?php

/**
 * @name IndexController
 * @author {&$AUTHOR&}
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends \Yaf\Controller_Abstract {

    /**
     * 默认初始化方法，如果不需要，可以删除掉这个方法
     * 如果这个方法被定义，那么在Controller被构造以后，Yaf会调用这个方法
     */
    public function init() {
        $this->getView()->assign("header", "Yaf Example");
    }

    /**
     * 
     * @param type $name
     * @return boolean
     */
    public function indexAction() {
        echo 123;
        return false;
    }
    
    public function aaaAction() {
        echo 123;
        return false;
    }

}
