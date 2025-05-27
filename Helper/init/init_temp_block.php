<?php
require_once __DIR__ . "/../initClass.php";

$userId = (int) ($_GET['id'] ?? 0);
if ($userId <= 0) {
    $response->redirect('users.php', ['token' => $user->token]);
    exit;
}

$sql = "SELECT login FROM User WHERE id = $userId LIMIT 1";
$result = $db->query($sql);
if ($result && $result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    $response->redirect('users.php', ['token' => $user->token]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateBlock = $_POST['date_block'] ?? '';
    $dateBlock = trim($dateBlock);

    if ($dateBlock) {
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $dateBlock);
        if ($dateTime !== false) {
            $formatted = $dateTime->format('Y-m-d H:i:s');
            if ($user->blockTemporarily($userId, $formatted)) {
                echo "Пользователь временно заблокирован";
            } else {
                echo "Ошибка блокировки";
            }
        } else {
            echo "Некорректный формат даты.";
        }
    } else {
        echo "Дата не введена.";
    }

}