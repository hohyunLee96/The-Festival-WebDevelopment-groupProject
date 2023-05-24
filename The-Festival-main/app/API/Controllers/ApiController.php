<?php

class ApiController
{
    function respond($data)
    {
        $this->respondWithCode(200, $data);
    }

    function respondWithError($httpcode, $message)
    {
        $data = array('errorMessage' => $message);
        $this->respondWithCode($httpcode, $data);
    }

    private function respondWithCode($httpcode, $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpcode);
        echo json_encode($data);
    }

    protected function sendHeaders(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: *");
        header('Content-Type: application/json');
    }

    function createObjectFromPostedJsonWithSetters($className, $data)
    {
        $object = new $className();
        foreach ($data as $key => $value) {
            // Construct the setter method name
            $setterMethod = 'set' . ucfirst($key);
            $object->$setterMethod(htmlspecialchars($value));
        }
        return $object;
    }
    protected function getSanitizedData()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        return $this->sanitize($data);
    }
    protected function sanitize($data)
    {
        if (is_object($data)) {
            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    $data->$key = htmlspecialchars($value);
                }
            }
        } else if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    $data[$key] = htmlspecialchars($value);
                }
            }
        }
        return $data;
    }
}