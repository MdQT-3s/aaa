<?php

class CommentClass extends Data
{
    public $id = null;
    public $post_id = null;
    public $parent_id = 0;
    public $user_id = null;
    public $content = null;
    public $created_at = null;
    public $login;


    public $validation_content = null;

    public User $user;
    public MySql $db;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->db = $user->db;
    }
    public function load(array $data): void
    {
        parent::loadData($this, $data);
    }

    public function validate(): bool
    {
        $result = parent::validateData($this);
        if (empty($this->content)) {
            $this->validation_content = 'Комментарий не может быть пустым';
            $result = true;
        }
        return $result;
    }

    public function save(): bool
{
    $postId = (int) $this->post_id;
    $userId = (int) $this->user_id;
    $content = $this->db->real_escape_string($this->content);

    $parentId = $this->parent_id ? (int)$this->parent_id : 'NULL';

    $sql = "
        INSERT INTO comment (post_id, parent_id, user_id, content, created_at)
        VALUES ($postId, $parentId, $userId, '$content', NOW())
    ";

    return $this->db->query($sql);
}

    

    public function get_post_id(int $postId): array
    {
        $sql = "
            SELECT c.*, u.login 
            FROM comment c 
            JOIN User u ON c.user_id = u.id 
            WHERE c.post_id = $postId 
            ORDER BY c.created_at ASC
        ";

        $comments = [];
        $result = $this->db->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $comment = new static($this->user);
                $comment->id = $row['id'];
                $comment->post_id = $row['post_id'];
                $comment->parent_id = $row['parent_id'];
                $comment->user_id = $row['user_id'];
                $comment->content = self::convert_br($row['content']);
                $comment->created_at = $row['created_at'];
                $comment->login = $row['login'];
                $comments[] = $comment;
            }
        }
        return $comments;
    }

    public function delete(int $commentId, int $userId, bool $isAdmin): bool
    {
        $sqlCheck = "
        SELECT user_id FROM comment WHERE id = $commentId
    ";
        $result = $this->db->query($sqlCheck);

        if ($result && $row = $result->fetch_assoc()) {
            if ((int) $row['user_id'] === $userId || $isAdmin) {
                $sqlDelete = "DELETE FROM comment WHERE id = $commentId";
                return $this->db->query($sqlDelete);
            }
        }

        return false;
    }

    public function renderCommentsTree(array $comments, $post, $user, $response, callable $DeleteComment, int $parentId = 0): void
    {
        $children = array_filter($comments, fn($c) => ((int) $c->parent_id === $parentId));

        if (!$children) {
            return;
        }

        echo '<ul class="comment-list">';
        foreach ($children as $comment) {
            ?>
            <li class="conteiner">
                <div class="comment-body">
                    <div class="d-flex justify-content-between">
                        <h3><?= $comment->login ?></h3>
                        <?php if ($DeleteComment($comment)): ?>
                            <a href="<?= $response->getLink('post.php', ['id' => $post->id, 'delete_comment' => $comment->id]) ?>"
                                class="text-danger" style="font-size: 1.8em;" title="Удалить"
                                onclick="return confirm('Удалить комментарий?')">🗑</a>
                        <?php endif; ?>
                    </div>
                    <div class="meta">
                        <?= $post->formatPostDate($comment->created_at) ?>
                    </div>
                    <p><?= $comment->content ?? '' ?></p>
                </div>

                <?php
                $this->renderCommentsTree($comments, $post, $user, $response, $DeleteComment, $comment->id);
                ?>
            </li>
            <?php
        }
        echo '</ul>';
    }

}








