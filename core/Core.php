<?php
/**
 * Created by PhpStorm.
 * User: lamb
 * Date: 2019/4/22
 * Time: 3:02 PM
 */

//核心框架
class Core
{
    //运行程序
    public function run()
    {
        spl_autoload_register(array($this, 'loadClass'));
        $this->setReporting();
        $this->removeMagicQuotes();
        $this->unregisterGlobal();
        $this->route();
    }

    //路由处理
    public function route()
    {
        $controllerName = 'Index';
        $action = 'index';
        if (!empty($_GET['url'])) {
            $url = $_GET['url'];
            $urlArray = explode('/', $url);
            //获取控制器名
            //ucfirst将字符串首字母转为大写
            $controllerName = ucfirst($urlArray[0]);
            //获取动作名
            //array_shift去掉数组第一个元素
            array_shift($urlArray);
            $action = empty($urlArray[0]) ? 'index' : $urlArray[0];
            //获取URL参数
            array_shift($urlArray);
            $queryString = empty($urlArray) ? array() : $urlArray;
        }
        //参数为空处理
        $queryString = empty($queryString) ? array() : $queryString;
        //实例化控制器
        $controller = $controllerName . 'Controller'; //拼凑完整的控制器名称
        $dispatch = new $controller($controllerName, $action);
        if(method_exists($controller,$action)){
            //回调方法，第一个参数array(对象，方法),第二个参数是array(参数1，参数2，...)
            call_user_func_array(array($dispatch,$action),$queryString);
        }else{
            exit($controller . '控制器不存在');
        }
    }

    //检测开发环境
    public function setReporting()
    {
        if (APP_DEBUG === true) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
            ini_set('error_log', RUNTIME_PATH . 'logs/error.log');


        }
    }

    //删除敏感字符
    public function stripSlashesDeep($value)
    {
        //递归调用，删除反斜杠
        $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
        return $value;
    }

    //检测敏感字符
    public function removeMagicQuotes()
    {
        if (get_magic_quotes_gpc()) {
            $_GET = $this->stripSlashesDeep($_GET);
            $_POST = $this->stripSlashesDeep($_POST);
            $_COOKIE = $this->stripSlashesDeep($_COOKIE);
            $_SESSION = $this->stripSlashesDeep($_SESSION);
        }

    }

    //检测自定义全局变量（register global）并移除
    public function unregisterGlobal()
    {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_COOKIE', '_POST', '_GET', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $val) {
                    if ($val === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    //自动加载控制器和模型类
    public static function loadClass($class)
    {
        $frameworks = FRAME_PATH . $class . '.class.php';
        $controllers = APP_PATH . 'application/controllers/' . $class . '.class.php';
        $models = APP_PATH . 'application/models/' . $class . '.class.php';
        //echo $frameworks . '<br>' .$controllers .'<br>'.$models;
        if (file_exists($frameworks)) {
            //加载核心类
            include $frameworks;
        } elseif (file_exists($controllers)) {
            //加载控制器
            include $controllers;
        } elseif (file_exists($models)) {
            //加载模型
            include $models;
        } else {
            /*代码错误*/
            die("ERROR");
        }
    }
}