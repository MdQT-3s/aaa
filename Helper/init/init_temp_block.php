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
            
            error_log("Trying to block user $userId until $formatted");
            
            if ($user->blockTemporarily($userId, $formatted)) {
                $response->redirect('users.php', ['token' => $user->token]);
                exit;
            } else {
                $error = "Ошибка при выполнении запроса блокировки";
                error_log("Block temporarily failed for user $userId");
            }
        } else {
            $error = "Некорректный формат даты. Используйте YYYY-MM-DDTHH:MM";
            error_log("Invalid date format: $dateBlock");
        }
    } else {
        $error = "Дата не введена";
    }
}

