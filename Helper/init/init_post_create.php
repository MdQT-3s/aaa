<?php require_once __DIR__ . "/../initClass.php";

$id = $_GET['id'] ?? null;
if ($user->isAdmin) {
    $response->redirect('index.php');
    exit;
}

if ($id && is_numeric($id)) {
    $post = new PostClass($user);
    if (!$post->findOne((int)$id)) {
        $response->redirect('index.php');
        exit;
    }

    if ($post->user->id != $user->id && !$user->isAdmin) {
        $response->redirect('index.php');
        exit;
    }

} else {
    $post = new PostClass($user);
}

if ($request->isPost && !$user->isGuest) {
    $post->load($request->post());
    if (!$post->validate()) {
        if ($post->save()) {
            $response->redirect('post.php?id=' . $post->id);
            exit;
        }
    }
}