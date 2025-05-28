<?php
require_once __DIR__ . "/../initClass.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $response->redirect('index.php');
    exit;
}

$postId = (int) $_GET['id'];
$post = new PostClass($user);


if (!$post->findOne($postId)) {
    $response->redirect('index.php');
    exit;
}

if (!isset($post->author)) {
    $post->author = null;
}


$canEditPost = !$user->isGuest && $post->author && ($user->id === $post->author->id || $user->isAdmin);
$canDeletePost = $canEditPost;
$canComment = !$user->isGuest && !$user->isAdmin;
$canDeleteComment = function ($comment) use ($user) {
    return !$user->isGuest && ($user->id === $comment->user_id || $user->isAdmin);
};


$commentClass = new CommentClass($user);

//deletPost
if (
    !$user->isGuest &&
    isset($_GET['action']) &&
    $_GET['action'] === 'delete' &&
    isset($_GET['id'])
) {
    $post_delete = new PostClass($user);
    if (
        $post_delete->findOne((int) $_GET['id']) &&
        $post_delete->author &&
        ($user->id == $post_delete->author->id || $user->isAdmin)
    ) {
        $comments = $commentClass->get_post_id($post_delete->id);
        if (!empty($comments)) {
            foreach ($comments as $comment) {
                $commentClass->delete($comment->id, $user->id, $user->isAdmin);
            }
        }

        $post_delete->delete();
        $response->redirect('index.php');
        exit;
    }

}

//deletComm
if (
    !$user->isGuest &&
    isset($_GET['delete_comment']) &&
    is_numeric($_GET['delete_comment'])
) {
    $commentId = (int) $_GET['delete_comment'];

    if ($commentClass->delete($commentId, $user->id, $user->isAdmin)) {
        $response->redirect('post.php', ['id' => $postId]);
        exit;
    } else {
        $error = 'Недостаточно прав для удаления комментария или комментарий не найден.';
    }
}

// ++Comm
if (
    !$user->isGuest &&
    !$user->isAdmin &&
    isset($_POST['add_comment']) &&
    isset($_POST['comment_text']) &&
    trim($_POST['comment_text']) !== ''
) {
    if ($post->author && $user->id === $post->author->id) {
        $error = 'Нельзя комментировать свой собственный пост.';
    } else {
        $commentText = $request->cleanParams($_POST['comment_text']);

        $newComment = new CommentClass($user);
        $newComment->post_id = $postId;
        $newComment->user_id = $user->id;
        $newComment->content = $commentText;

        if ($newComment->save()) {
            $response->redirect("post.php", ['id' => $postId]);
            exit;
        } else {
            $error = 'Ошибка при сохранении комментария.';
        }
    }
}

$comments = $commentClass->get_post_id($postId);
