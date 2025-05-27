<?php
include_once __DIR__ . "/autoload.php";
$dbConfig = require __DIR__ . "/config/db_config.php";
$menuItems = require __DIR__."/config/menu_items.php";

$request = new Request();
$db = new MySql($dbConfig);
$user = new User($request,$db);
$response = new Response($user);
$menu = new Menu($menuItems,$response);
$post = new PostClass($user);






?>