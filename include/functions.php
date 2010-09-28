<?php

//连接数据库，返回一个SimpleDB类的实例
function connect_db($type = 'sqlite')
{
    switch ($type)
    {
    case 'sqlite':
        return connect_sqlite();
    case 'mysql':
        return connect_mysql();
    default:
        display_msg("不支持的数据库类型");
    }
}

//连接数据库，返回一个sqlite类的实例
function connect_sqlite() {
    static $db = null;
    if (is_null($db))
    {
        require_once(ROOT . "/include/sqlite.php");
        $db = new sqlite(config::$db_file);
        if ($db->errno)
            display_msg('连接sqlite数据库出错: ' . $db->error);
    }
    return $db;
}

//连接数据库，返回一个mysql类的实例
function connect_mysql() {
    static $db = null;
    if (is_null($db))
    {
        require_once(ROOT . "/include/mysql.php");
        $db = new mysql(config::$db_host, config::$db_user,
                        config::$db_pass, config::$db_name);
        if ($db->errno)
            display_msg('连接MySQL数据库出错: ' . $db->error);
    }
    return $db;
}

//从 file 中读取配置信息 id|name|description
function load_list($file) {
    static $arr_list = array();
    if (!isset($arr_list[$file]))
    {
        $lines = file($file);
        $list = array();
        foreach ($lines as $line)
        {
            $line = trim($line);
            if ($line{0} == '#' || empty($line))
                continue;
            list($pid, $pname, $pdesc) = explode('|', $line, 3);
            $list[$pid] = array(
                'id'   => $pid,
                'name'  => $pname,
                'desc'  => $pdesc,
            );
        }
        ksort($list);
        $arr_list[$file] = $list;
    }
    return $arr_list[$file];
}

//设置登录
function do_login()
{
    if (isset($_REQUEST['username']) && isset($_REQUEST['password']) &&
        $_REQUEST['username'] == config::$admin_user &&
        $_REQUEST['password'] == config::$admin_pass)
    {
        $_SESSION[config::SESS_NAME]['admin'] = $_SERVER['REMOTE_ADDR'];
        return true;
    }
    else 
    {
        unset($_SESSION[config::SESS_NAME]['admin']);
        return false;
    }
}

//设置登出
function do_logout()
{
    unset($_SESSION[config::SESS_NAME]['admin']);
}

//检查是否已经登录为管理员
function check_login() {
    if (isset($_SESSION[config::SESS_NAME]['admin']) &&
        $_SESSION[config::SESS_NAME]['admin'] == $_SERVER['REMOTE_ADDR'])
    {
        return true;
    }
    else 
    {
        return false;
    }
}

/*
 * 检查参数, haystack是包含参数的数组，arg_filter是过滤条件，例:
 *   $args = filter($_REQUEST, array(
 *          'username'  => array('regex'    => '/[a-zA-Z0-9_]{6,16}/'),
 *          'password'  => array('length'   => '6~16'),
 *          'gender'    => array('empty'    => ''),
 *          'age'       => array('numeric   => '0~150'),
 *          'test'      => array('empty'    => '',
 *                               'length'   => '7~11'),
 *      ), $err);
 *   if (false === $args)
 *      die($err);
 *   echo $args['username'], $args['password'];
 * $err为错误信息
 * 返回所有需要过滤的k/v组成的数组
 */
function filter($haystack, $arg_filters, &$err)
{
    $arr_ret = array();
    foreach ($arg_filters as $name => $filters) 
    {
        $err = "Bad arg '$name'";

        if (!isset($haystack[$name])) {
            return false;
        }

        $value = $haystack[$name];

        if (!is_array($filters) && isset($filters['type']))
            $filters = array($filters);

        foreach ($filters as $type => $filter) {
            switch($type) {
                case 'empty':
                    if (strlen($value) > 0) {
                        $err .= ': empty' ;
                        return false;
                    }
                    break;
                case 'numeric':
                    if (!is_numeric($value)) {
                        $err .= ': not numeric';
                        return false;
                    }
                    $value += 0;
                    list($min, $max) = explode('~', $filter);
                    if (isset($min{0}) && $value < $min) {
                        $err .= ': numeric too small';
                        return false;
                    }
                    if (isset($max{0}) && $value > $max) {
                        $err .= ': numeric too large';
                        return false;
                    }
                    break;
                case 'length':
                    list($min, $max) = explode('~', $filter);
                    if (isset($min{0}) && mb_strlen($value, "UTF-8") < $min) {
                        $err .= ': length not enough';
                        return false;
                    }
                    if (isset($max{0}) && mb_strlen($value, "UTF-8") > $max) {
                        $err .= ': length exceed maxlen';
                        return false;
                    }
                    break;
                case 'regex':
                    if (!preg_match($filter, $value)) {
                        $err .= ': do not match regex';
                        return false;
                    }
                    break;
                case 'callback':
                    if (!call_user_func($filter, $value)) {
                        $err .= ": callback '$filter' returned false";
                        return false;
                    }
                    break;
                default:
                    $err .= ": unknown filter '$filter'";
                    return false;
            }
        } //end of each filters
        $arr_ret[$name] = $value;
    } //end of each arg_filters
    $err = '';
    return $arr_ret;
}

//用于输出 JSON
function display_json($arr_data)
{
    if (!isset($arr_data['result']))
    {   //no error
        $arr_data['result'] = 0; 
        $arr_data['err']    = 'ok';
    }

    $arr_data['result'] = intval($arr_data['result']);

    if ($arr_data['result'] > 0 && !isset($arr_data['err']))
    {
        $arr_data['err'] = '未知错误';
    }

    ob_clean();

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($arr_data);

    exit();
}

//用于显示通用的信息页面(出错或者其他)
function display_msg($msg, $title = "出错啦!")
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
    {
        display_json(array(
            'result'    => 1000,
            'err'       => $msg,
        ));
        exit();
    }

    echo <<<eot
<center>
<div style="margin:20px; width:500px;">
<div style="text-align:center;font-size:24px; border: 1px solid gray; padding:10px;">$title</div>
<div style="text-align:left;padding:10px; border:1px solid gray; height:150px;">
$msg
</div>
<div style="border: 1px solid gray;padding: 5px;">
    <a href="javascript:history.back(1);">后退</a> &nbsp; | &nbsp;
    <a href="index.php">首页</a>
</div>
</div>
</center>
eot;
    require("include/footer.php");
    die();
}

