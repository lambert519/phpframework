<?php
/**
 * Created by PhpStorm.
 * User: lamb
 * Date: 2019/4/23
 * Time: 2:26 PM
 */

class View
{
    protected $variables = array();
    protected $_controller;
    protected $_action;

    function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
    }

    //分配变量
    function assign($name, $value)
    {
        $this->variables[$name] = $value;
    }

    //渲染
    function render()
    {
        //extract函数，将数组键值定义为变量，变量值为数组值。
        extract($this->variables);
        $defaultHeader = APP_PATH . 'application/views/header.php';
        $defaultFooter = APP_PATH . 'application/views/footer.php';
        $controllerHeader = APP_PATH . 'application/views/' . $this->_controller . '/header.php';
        $controllerFooter = APP_PATH . 'application/views/' . $this->_controller . '/footer.php';
        //页头文件
        if (file_exists($controllerHeader)) {
            include($controllerHeader);
        } else {
            include($defaultHeader);
        }
        //页内容文件
        include(APP_PATH . 'application/views/' . $this->_controller . '/' . $this->_action . '.php');

        if(file_exists($controllerFooter)){
            include($controllerFooter);
        }else{
            include($defaultFooter);
        }
    }
}