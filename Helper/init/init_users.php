<?php
require_once __DIR__ . "/../initClass.php";

if ($user->isGuest || !$user->isAdmin) {
    $response->redirect('index.php');
    exit;
}
$currentTime = date('Y-m-d H:i:s');
$db->query("DELETE FROM bun WHERE date_block IS NOT NULL AND date_block < '$currentTime'");

$users = [];

$sql = "SELECT 
            u.id, u.name, u.surname, u.login, u.email,
            b.created_at, b.date_block
        FROM User u
        LEFT JOIN bun b ON b.id_user = u.id";

$result = $db->query($sql);

$now = date('Y-m-d H:i:s');


if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $isBlocked = false;
        $isPermanentlyBlocked = false;
        $blockInfo = null;
        if (!is_null($row['date_block'])) {
            if ($row['date_block'] >= $now) {
                $isBlocked = true;
                $blockInfo = "Забанен до " . $row['date_block'];
            }
        }
        elseif (is_null($row['date_block']) && !is_null($row['created_at'])) {
            $isBlocked = true;
            $isPermanentlyBlocked = true;
            $blockInfo = "Забанен навсегда";
        }

        $users[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'surname' => $row['surname'],
            'login' => $row['login'],
            'email' => $row['email'],
            'isBlocked' => $isBlocked,
            'isPermanentlyBlocked' => $isPermanentlyBlocked,
            'blockDate' => $row['date_block'],
            'blockInfo' => $blockInfo
        ];
    }
}
