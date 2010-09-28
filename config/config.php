<?php

final class config 
{
    const TIMEZONE  = 'Asia/Shanghai';
    const SESS_NAME = 'SIMPLE';
    const LOG_PATH  = 'log/simple.log';

    //sqlite
    static $db_file = "data/simple.db";

    //mysql
    static $db_host = "localhost";
    static $db_user = "root";
    static $db_pass = "123456";
    static $db_name = "simple";

    //此系统管理
    static $admin_user = "root";
    static $admin_pass = "123456";

    //页面标题
    static $title   = '默认页面标题';
    static $titles  = array(
        'index'     => '页面标题',
        'login'     => '管理登录',
        'do_login'  => '管理登录',
        'logout'    => '注销系统',
        'test'      => '测试',
        'install'   => '安装',
        );

    static $action;
    static $client_ip;
    static $method;
    static $is_login;
    static $logid;

    public static function init()
    {
        //根据$_REQUEST['action']分发请求
        if (!isset($_REQUEST['action']))
        {
            self::$action = 'index';
        }
        else {
            self::$action = $_REQUEST['action'];
        }

        self::$method           = $_SERVER['REQUEST_METHOD'];

        //ip
        self::$client_ip        = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            self::$client_ip    = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        self::$logid = crc32(microtime() . ip2long(self::$client_ip)) & 0x7FFFFFFF;

        //判断是否登录
        self::$is_login = check_login();

        if (isset(self::$titles[self::$action]))
        {
            self::$title = self::$titles[self::$action];
        }

        if (false === logger::log_open(config::LOG_PATH))
        {
            display_msg("无法打开日志文件");
        }

        logger::log_add_info('method:' . self::$method);
        logger::log_add_info('action:' . self::$action);
        logger::log_add_info('logid:' . self::$logid);
        logger::log_add_info('ip:' . self::$client_ip);
    }
}

?>
