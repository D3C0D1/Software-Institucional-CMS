<?php
ini_set('display_errors',1); error_reporting(E_ALL);
require_once __DIR__ . '/../../config.php';
$host = DB_HOST; $user = DB_USER; $pass = DB_PASS; $charset = DB_CHARSET; $nuevo = DB_NAME; $viejo = isset($_GET['old']) ? $_GET['old'] : 'blog_cecar';
$opt = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES=>false];
$root = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $opt);
$root->exec("CREATE DATABASE IF NOT EXISTS `$nuevo` CHARACTER SET $charset COLLATE utf8mb4_unicode_ci");
$pdoOld = new PDO("mysql:host=$host;dbname=$viejo;charset=$charset", $user, $pass, $opt);
$pdoNew = new PDO("mysql:host=$host;dbname=$nuevo;charset=$charset", $user, $pass, $opt);
$pdoNew->exec('SET FOREIGN_KEY_CHECKS=0');
$tbls = $pdoOld->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
foreach ($tbls as $t) {
  $row = $pdoOld->query("SHOW CREATE TABLE `$t`")->fetch();
  $sql = isset($row['Create Table']) ? $row['Create Table'] : $row[1];
  if (!$sql) { continue; }
  $sql = str_replace("CREATE TABLE `".$t."`", "CREATE TABLE IF NOT EXISTS `".$t."`", $sql);
  $pdoNew->exec($sql);
}
$pdoNew->exec('SET FOREIGN_KEY_CHECKS=1');
foreach (['categorias','usuarios'] as $t) {
  $pdoNew->exec("DELETE FROM `$t`");
  $pdoNew->exec("INSERT INTO `$t` SELECT * FROM `$viejo`.`$t`");
}
$cats = $pdoNew->query('SELECT COUNT(*) c FROM categorias')->fetch()['c'];
$users = $pdoNew->query('SELECT COUNT(*) c FROM usuarios')->fetch()['c'];
header('Content-Type: application/json');
echo json_encode(['ok'=>true,'db'=>$nuevo,'from'=>$viejo,'categorias'=>$cats,'usuarios'=>$users]);
?>