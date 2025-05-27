<?php

class Data
{
    public static function validateData($model): bool
    {
        $data_errors = false;
        $vars = get_object_vars($model);
        $optional =['patronymic','id','token'];

        foreach ($vars as $key => $value) {
            if (strpos($key, 'validation_') === 0) {
                $field = substr($key, strlen('validation_'));
                if(in_array($field,$optional)){
                    continue;
                }
                if (property_exists($model, $field)) {
                    $fieldValue = trim($model->$field);
                    if ($fieldValue === '') {
                        $model->$key = "Поле \"$field\" не может быть пустым.";
                        $data_errors = true;
                    }
                }
            }
        }

        return $data_errors;
    }

    public static function loadData($model, array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($model, $key)) {
                $model->$key = $value;
            }
        }
    }

    // "\r\n" -->> "<br/>"
    public static function convert_rn(string $data): string
    {
        return preg_replace('/\v+|\\\r\\\n/ui', '<br/>', $data);
    }
    //  "<br/>" -->> "\r\n"
    public static function convert_br(string $data): string
    {
        return preg_replace('/<br\s*\/?>/i', "\r\n", $data);
    }

    public static function formatDate(string $date): string
    {
        $date_time = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if ($date_time === false) {
            return $date;
        }
        return $date_time->format('d.m.Y H:i:s');
    }

}