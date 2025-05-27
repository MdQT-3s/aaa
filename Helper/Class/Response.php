<?php

class Response{

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
         if (isset($_GET['token'])) {
            $this->user->token = $_GET['token'];
            $this->user->identity();
            
            if ($this->user->isGuest) {
                $this->redirect('index.php');
            }
        }
    }
    public function getLink(string $url, array $params = []): string
    {
        if (!$this->user->isGuest && !isset($params['token'])) {
            $params['token'] = $this->user->token;
        }

        $query = http_build_query($params);
        if ($query) {
            $str_split = strpos($url, '?') === false ? '?' : '&';
            $url .= $str_split . $query;
        }

        return $url;
    }

    public function redirect(string $url, array $params = []): void
    {
        $fullUrl = $this->getLink($url, $params);

        $host = $this->user->request->GetHost();
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

        header("Location: $protocol://$host/$fullUrl");
        exit;
    }

}