<?php
/*
MYSQL:
    $ mysql -uroot -p
    CREATE DATABASE simple DEFAULT CHARSET utf8;
    USE simple;
    CREATE TABLE t (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name CHAR(255)
    ) DEFAULT CHARSET utf8;

SQLITE:
    $ sqlite3 data/simple.db
    CREATE TABLE t (id INTEGER PRIMARY KEY, name CHAR(255));
 */

//$db = connect_db('mysql');
$db = connect_db('sqlite');

$sql = 'INSERT INTO t VALUES(NULL, "中文测试")';
$db->exec($sql);
if ($db->errno)
    die($db->error);
echo "Insert ID: ", $db->insert_id, "<br/>\n";
echo "Affected Rows: ", $db->affected_rows, "<br/>\n";

$sql = "SELECT * FROM t";

$rows = $db->query($sql);
if ($db->errno)
    die($db->error);

print_r($rows);

?>
