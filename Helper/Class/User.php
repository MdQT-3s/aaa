<?php

class User
{

    public $NameTable = 'User'; //1) имя таблицы пользователя из базы данных;

    public $id = null;
    public $name = null;
    public $surname = null;
    public $patronymic = null; //2) все атрибуты из формы регистрации и таблицы базы данных пользователя;
    public $login = null;
    public $email = null;
    public $password = null;
    public $password_repeat = null;
    public $token = null;
    public $status_admin = 0;

    public $validation_id = null;
    public $validation_name = null;
    public $validation_surname = null;
    public $validation_patronymic = null;  //3) атрибуты валидации полей регистрации пользователя;
    public $validation_login = null;
    public $validation_email = null;
    public $validation_password = null;
    public $validation_password_repeat = null;
    public $validation_token = null;

    public $login_admin = 'admin';
    public $password_admin = 123; //4) логин и пароль администратора;

    public bool $isGuest = true; //5) isGuest 
    public bool $isAdmin = false; //5)isAdmin;


    public Request $request; //6)Request 
    public MySql $db; //6)MySql.

    public function __construct($request, $db)
    {

        $this->request = $request;
        $this->db = $db;

        if (!empty($_GET['token'])) {
            $this->token = $_GET['token'];
            $this->identity();
        }
    }

    public function load($data)
    {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
        $this->isAdmin = $this->isAdmin();
    }

    public function validateRegister()
    {
        $result = Data::validateData($this);
        if ($this->password !== $this->password_repeat) {
            $this->validation_password_repeat = 'Пароли должны совпадать';
            $result = true;
        }

        if (!empty($this->login) && $this->db->uniqCheck('User', 'login', $this->login)) {
            $this->validation_login = 'Логин уже занят';
            $result = true;
        }

        if (!empty($this->email) && $this->db->uniqCheck('User', 'email', $this->email)) {
            $this->validation_email = 'Email уже существует';
            $result = true;
        }

        return $result;
    }
    public function save()
    {
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        if ($hashedPassword === false) {
            return false;
        }

        $sql = "INSERT INTO User (name, surname, patronymic, login, email, password, token, status_admin) 
            VALUES ('$this->name',
        '$this->surname',
        '$this->patronymic',
        '$this->login',
        '$this->email',
        '$hashedPassword',
        '$this->token',
        '$this->status_admin')";

        return $this->db->query($sql);
    }

    public function validateLogin()
    {
        $result = false;
        $this->validation_login = null;
        $this->validation_password = null;

        if (empty($this->login)) {
            $this->validation_login = "Логин не может быть пустым";
            $result = true;
        }

        if (empty($this->password)) {
            $this->validation_password = "Пароль не может быть пустым";
            $result = true;
        }

        return $result;
    }

    public function login()
    {
        if ($this->validateLogin()) {
            return false;
        }

        $sql = "SELECT * FROM {$this->NameTable} WHERE login = '{$this->login}' LIMIT 1";
        $result = $this->db->query($sql);
        if (!$result || $result->num_rows === 0) {
            $this->validation_login = "Пользователь с таким логином не найден";
            return false;
        }

        $user_data = $result->fetch_assoc();

        if (!password_verify($this->password, $user_data['password'])) {
            $checkUser = new self($this->request, $this->db);
            $checkUser->load($user_data);
            
            if ($checkUser->isBlocked()) {
                $this->validation_login = "Пользователь заблокирован.";
                return false;
            }
            $this->validation_password = "Неверный пароль";
            return false;
        }

        $this->load($user_data);
        $this->isGuest = false;

        // Token
        $this->token = bin2hex(random_bytes(16));
        $sqlUpdate = "UPDATE {$this->NameTable} SET token = '{$this->token}' WHERE id = {$this->id}";
        $this->db->query($sqlUpdate);

        return true;
    }

    public function identity()
    {
        if (empty($this->token)) {
            $this->isGuest = true;
            return false;
        }

        $sql = "SELECT * FROM {$this->NameTable} WHERE token = '{$this->token}' LIMIT 1";
        $result = $this->db->query($sql);

        if (!$result || $result->num_rows === 0) {
            $this->isGuest = true;
            return false;
        }

        $userData = $result->fetch_assoc();
        $this->load($userData);
        $this->isGuest = false;
        return true;
    }

    public function isAdmin()
    {
        return ($this->status_admin == 1);
    }

    public function logout()
    {
        if ($this->isGuest) {
            return;
        }

        $sqlUpdate = "UPDATE {$this->NameTable} SET token = NULL WHERE id = {$this->id}";
        $this->db->query($sqlUpdate);

        $Properties = ['request', 'db', 'isGuest', 'isAdmin'];
        foreach (get_object_vars($this) as $key => $value) {
            if (!in_array($key, $Properties)) {
                $this->$key = null;
            }
        }
        $this->isGuest = true;
        $this->isAdmin = false;
    }




    public function isBlocked(): bool
    {
        $sql = "SELECT date_block FROM bun WHERE id_user = {$this->id} LIMIT 1";
        $result = $this->db->query($sql);
        if (!$result || $result->num_rows === 0) {
            return false;
        }
        $row = $result->fetch_assoc();

        // Если date_block NULL — это постоянный бан
        if (is_null($row['date_block'])) {
            return true;
        }

        $now = date('Y-m-d H:i:s');
        return $now <= $row['date_block'];

    }


    public function blockTemporarily(int $userId, string $dateBlock): bool
{
    $createdAt = date('Y-m-d H:i:s');

    $sql = "INSERT INTO bun (id_user, date_block, created_at) VALUES ($userId, '$dateBlock', '$createdAt')
            ON DUPLICATE KEY UPDATE date_block = '$dateBlock', created_at = '$createdAt'";
    $result = $this->db->query($sql);
    echo "Ошибка: " . $this->db->error;

    if (!$result) {
        echo "Ошибка: " . $this->db->error;

        error_log("SQL error: " . $this->db->error);
        return false;
    }
    return true;
}




    public function blockPermanently(int $userId): bool
    {
        $createdAt = date('Y-m-d H:i:s');

        $sql = "INSERT INTO bun (id_user, date_block, created_at)
                VALUES ($userId, NULL, '$createdAt')
                ON DUPLICATE KEY UPDATE
                    date_block = NULL,
                    created_at = '$createdAt'";

        $ok = $this->db->query($sql);

        $postsResult = $this->db->query("SELECT id FROM posts WHERE user_id = $userId");

        if ($postsResult && $postsResult->num_rows > 0) {
            while ($row = $postsResult->fetch_assoc()) {
                $postId = (int) $row['id'];
                $this->db->query("DELETE FROM comment WHERE post_id = $postId");
                $this->db->query("DELETE FROM posts WHERE id = $postId");
            }
        }

        return $ok;
    }

}
