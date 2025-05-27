 <?php
class Request
{
    public bool $isPost;
    public bool $isGet;

    public function __construct()
    {
        $this->isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
        $this->isGet = $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public function cleanParams($data)
    {
        if (is_array($data)) {
            return $this->cleanArray($data);
        }
        
        return strip_tags(trim($data));
    }

    public function cleanArray(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = is_array($value) 
                ? $this->cleanArray($value) 
                : $this->cleanParams($value);
        }
        return $result;
    }

    public function post($key = null)
    {
        if (isset($key)) {
            if(isset($_POST[$key])){
                return $this->cleanParams($_POST[$key]);
            }else{
                return null;
            }
        }else{
            return $this->cleanParams($_POST);
        }
    }

    public function get($key = null)
    {
        if (isset($key)) {
            if(isset($_GET[$key])){
                return $this->cleanParams($_GET[$key]);
            }else{
                return null;
            }
        }else{
            return $this->cleanParams($_GET);
        }
    }

    public function GetHost(): string
    {
        return $_SERVER['HTTP_HOST'];
    }

    public function token(): ?string
    {
        return $this->get('token');;
    }


}
