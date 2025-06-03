<?php
class Menu
{
    private $items;
    public Response $response;
    public function __construct(array $items,Response $response)
    {
        $this->items = $items;
        $this->response = $response;
    }
    public function createHtml()
    {
        $currentPath = basename($_SERVER['PHP_SELF']); 
        $html = '<ul>';
        foreach ($this->items as $item) {
            $title = $item['title'] ?? '';
            $file = $item['file'] ?? '#';
            if ($this->response->user->isGuest === false && in_array(strtolower($title), ['Вход', 'Регистрация'])) {
                continue;
            }
            if ($this->response->user->isAdmin === false && in_array(strtolower($title), ['Пользователи'])) {
                continue;
            }
            if ($this->response->user->isGuest === true && in_array(strtolower($title), ['Выход'])) {
                continue;
            }
            $params = $item['params'] ?? [];
            $url = $this->response->getLink($file, $params);
            $activeClass = ($currentPath === $item["file"]) ? ' class="colorlib-active"' : ''; 
            $html .= "<li $activeClass><a href=\"{$url}\">{$title}</a></li>"; 
        }
        $html .= '</ul>';
        return $html;
    }
}
