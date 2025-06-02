<?php
require_once __DIR__ . "/Helper/initClass.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = (int)($_POST['id'] ?? 0);

    if ($userId > 0) {
        $result = $user->blockPermanently($userId);

    }
    $response->redirect('users.php', ['token' => $user->token]);
    exit;
} else {
    $response->redirect('users.php', ['token' => $user->token]);

    exit;
}
