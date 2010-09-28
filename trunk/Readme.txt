需要安装php的pdo_sqlite、mb_string模块。

如果运行http server的用户名无法写入data/simple.db，请执行
  $ chmod 777 data
  $ chmod 666 data/simple.db

index.php   主入口
    完成初始化和分发

action      具体业务处理代码

    login.php       管理员登录页面
    dologin.php     登录提交
    logout.php      注销

config      配置信息

    config.php 
    配置参数

data        数据

    simple.db
    SQLITE3数据库。

include     被包含的文件

    functions.php   提供了几个比较有用的函数
    sqlite.php      对PDO_SQLITE3的封装
    mysql.php       对mysqli的封装，接口与sqlite.php的一致
    logger.php      日志类

    header.php      页面首部（在index.php包含）
    footer.php      页面尾部（在index.php包含）

static      用于存放静态文件，图片，js，css
