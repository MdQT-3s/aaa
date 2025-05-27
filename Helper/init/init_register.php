<?php

require_once __DIR__ . "/../initClass.php";

$errors = [];
$old = $_POST ?? [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User($request, $db);
    $user->load($_POST);
    $haserrors = $user->validateRegister();
    if (!$haserrors) {
        if (!$db->uniqCheck('User', 'email', $user->email)) {
            if ($user->save()) {
                 $response->redirect('index.php');
            } else {
                $errors[] = 'Ошибка при сохранении пользователя в базе данных';
            }
        } else {
            $user->validation_email = 'Email уже существует';
        }
    }
}
