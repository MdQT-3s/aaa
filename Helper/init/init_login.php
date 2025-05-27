<?php require_once __DIR__ . "/../initClass.php";

$user = new User($request, $db);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user->login = trim($_POST['login'] ?? '');
    $user->password = $_POST['password'] ?? '';

    $validationFailed = $user->validateLogin();
    if ($validationFailed) {

        if (!empty($user->validation_login)) {
            $errors['login'] = $user->validation_login;
        }
        if (!empty($user->validation_password)) {
            $errors['password'] = $user->validation_password;
        }
    } else {

        if ($user->login()) {
            header("Location: /index.php?token=" . urlencode($user->token));
            exit;
        } else {
            if (!empty($user->validation_login)) {
                $errors['login'] = $user->validation_login;
            }
            if (!empty($user->validation_password)) {
                $errors['password'] = $user->validation_password;
            }
            if (empty($errors)) {
                $errors['login'] = 'Неверный логин или пароль';
            }
        }
    }
}
