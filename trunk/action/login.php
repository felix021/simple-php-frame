<?php ob_clean(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="" /> 
<meta name="Keywords" content="" />
<title><?php echo config::$title; ?></title>
<link href="./static/style.css" rel="stylesheet" media="screen" type="text/css" /> 
<script src="./static/jquery.js" type="text/javascript"></script>

<script type="text/javascript">
	
</script>

</head>

<body>
<div id="body-main">
  <div id="logo-Pan">
    <a href="index.php"><img height="30" width="200" src="./static/images/logo.png" /></a>
  </div>
  
  <div id="login-Pan">
    <h1>管理登录</h1>
    <form action="index.php?action=do_login" method="POST">
      <p>用户名　<input style="width:150px" type="text" name="username"/></p>
      <p>密　码　<input style="width:150px" type="password" name="password"/></p>
      <p><input class="login-button" type="submit" value="登  录" /></p>
    </form>
  </div>
</div>
