<?php

class PostClass extends Data
{
    public $id = null;
    public $title = null;
    public $content = null;
    public $preview = null;
    public $created_at = null;

    public $author;


    public $validation_title = null;
    public $validation_content = null;
    public $validation_preview = null;

    public User $user;
    public MySql $db;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->db = $user->db;
    }
    public function validate(): bool
    {
        $result = parent::validateData($this);
        if (empty($this->title)) {
            $this->validation_title = 'Заголовок обязателен';
            $result = true;
        }

        if (empty($this->content)) {
            $this->validation_content = 'Содержание обязательно';
            $result = true;
        }
        if (empty($this->preview)) {
            $this->validation_preview = 'Превью обязательно';
            $result = true;
        }

        return $result;
    }
    public function load(array $data): void
    {
        parent::loadData($this, $data);
    }

    public function save()
    {
        if ($this->user->isGuest) {
            return false;
        }
        $title = $this->db->real_escape_string($this->title);
        $content = $this->db->real_escape_string(self::convert_rn($this->content));
        $preview = $this->db->real_escape_string($this->preview);
        $userId = (int) $this->user->id;

        if ($this->id) {
            $id = (int) $this->id;
            $sql = "
                UPDATE posts 
                SET title = '$title', content = '$content', preview = '$preview' 
                WHERE id = $id AND user_id = $userId
            ";
            return $this->db->query($sql);
        } else {
            $sql = "
                INSERT INTO posts (title, content, preview, user_id) 
                VALUES ('$title', '$content','$preview', $userId)
            ";
            $result = $this->db->query($sql);

            if ($result) {
                $this->id = $this->db->insert_id;
                return true;
            }

            return false;
        }
    }
    public function findOne(int $id): bool
    {
        $id = (int)$id; 
    
        $sql = "SELECT posts.*, User.id AS author_id, User.login AS author_login 
                FROM posts 
                JOIN User ON posts.user_id = User.id 
                WHERE posts.id = $id";
    
        $query_result = $this->db->query($sql);
        $row = $query_result ? $query_result->fetch_assoc() : false;
    
        if ($row) {
            $this->id = $row['id'];
            $this->title = $row['title'];
            $this->content = self::convert_br($row['content']);
            $this->preview = $row['preview'];
            $this->created_at = $row['created_at'];
    
            $this->author = (object) [
                'id' => $row['author_id'],
                'login' => $row['author_login']
            ];
    
            return true;
        }
    
        return false;
    }
    
    public function formatPostDate(string $date): string
    {
        return parent::formatDate($date);
    }


    public function getAll($limit = false)
    {
        if ($limit === false) {
            $countResult = $this->db->query("SELECT COUNT(id) as count FROM posts");
            $limit = $countResult ? (int) $countResult->fetch_assoc()['count'] : 0;
        }


        $sql = "SELECT 
                p.*,
                (SELECT COUNT(*) FROM comment c WHERE c.post_id = p.id) AS comments_count,
                u.login
            FROM posts p
            LEFT JOIN User u ON p.user_id = u.id
            ORDER BY p.created_at DESC";

        if (is_numeric($limit)) {
            $limit = (int) $limit;
            $sql .= " LIMIT $limit";
        }

        $result = [];
        $queryResult = $this->db->query($sql);

        if ($queryResult && $queryResult->num_rows > 0) {
            while ($row = $queryResult->fetch_assoc()) {
                $postUser = new User($this->user->request, $this->db);
                $postUser->id = $row['user_id'];
                $postUser->login = $row['login'];

                $post = new static($postUser);

                $post->id = $row['id'];
                $post->title = $row['title'];
                $post->content = self::convert_br($row['content']);
                $post->preview = $row['preview'];
                $post->created_at = $row['created_at'];
                
                $result[] = $post;
            }
        }

        return $result;
    }
    public function getLastPosts(): array
    {
        return $this->getAll(10);
    }


    public function delete(): bool
    {
        if ($this->user->isGuest || !$this->id) {
            return false;
        }
        $id = (int) $this->id;
        if ($this->user->isAdmin) {
            $sql = "DELETE FROM posts WHERE id = $id";
        } else {
            $userId = (int) $this->user->id;
            $sql = "DELETE FROM posts WHERE id = $id AND user_id = $userId";
        }
    
        return $this->db->query($sql);
    }

    public function addComment(string $text, int $userId): bool
    {
        $comment = new CommentClass($this->user);
        $comment->post_id = $this->id;
        $comment->user_id = $userId;
        $comment->content = $text;
        return $comment->save();
    }

    public function getComments(): array
    {
        $comment = new CommentClass($this->user);
        return $comment->get_post_id($this->id);
    }

    public function deleteComment(int $commentId, int $userId, bool $isAdmin): bool
    {
        $comment = new CommentClass($this->user);
        return $comment->delete($commentId, $userId, $isAdmin);
    }



}
