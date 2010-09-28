<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="" /> 
<meta name="Keywords" content="" />
<title><?php echo config::$title; ?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="./static/style.css" rel="stylesheet" media="screen" type="text/css" /> 
<script src="./static/jquery.js" type="text/javascript"></script>


<body>
<div id="head-Pan">
  <div id="logo">
    <img height="60" width="400" src="./static/images/logo.png" />
  </div>
  <div id="head-Right">
    <ul>
<?php
if (!config::$is_login) {
    echo <<<eot
        <li>身份：游客</li>
        <li><a href="index.php?action=login">管理员登录</a></li>
eot;
}
else {
    echo <<<eot
        <li>身份：管理员</li>
        <li><a href="index.php?action=logout">安全退出</a></li>
eot;
}
?>    </ul>
  </div>
</div>
