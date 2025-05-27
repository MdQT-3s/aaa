<?php require_once __DIR__ . "/../initClass.php";
$posts = $post->getAll();

$isAuthorized = !$user->isGuest;
$userId = $user->id;
$isAdmin = $user->isAdmin;

