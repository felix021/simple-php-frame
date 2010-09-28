<?php

if (do_login()) {
    display_msg("登录成功", "操作成功");
}
else {
    display_msg('登录失败: 用户名或密码错误。请确保已经打开浏览器的cookie支持。');
}
