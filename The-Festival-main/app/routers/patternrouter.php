<?php
class PatternRouter
{
    private function stripParameters($uri)
    {
        // checks if $uri contains ?, which shows the $uri contains parameters.
        if (str_contains($uri, '?')) {
            // using strpos find the locations of starting parameters (?) and return from pos 0 to there with substr.
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        return $uri;
    }

    private function getQueries($uri) {
        if (str_contains($uri, '?')) {
            return substr($uri, strpos($uri, '?')+1);
        }
        else {
            return "";
        }
    }

    // this method gets the $uri and maps it to the correct controller and method.
    // examples:
    // $uri: home/about will call: controllers/homecontroller->about("");
    // $uri: login will call: controllers/logincontroller->index("");
    // $uri: login/index?hello will call: controllers/logincontroller->index("?hello")
    // $uri: home/users?id=10 will call: controllers/homecontroller->users("?id=10");
    // $uri: test/hello will call: controllers/testcontroller->hello("")
    // $uri: api/test/hello will call: api/controllers/testcontroller->hello("")
    public function route($uri)
    {
        // exclude javascript and css fils.
        if (
            str_contains($uri, "public")
            || str_ends_with($uri, ".js")
            || str_ends_with($uri, ".css")
            || str_ends_with($uri, ".ts")
        ) {
            // echo $uri;
            // app\public\Javascripts\tinymce\js\tinymce\tinymce.min.js
            // echo __DIR__ . "/../public/" . $uri;
            // readfile($uri);
            // readfile("/" . $uri);
            readfile(__DIR__ . "/../public/" . $uri);
            // return();
            die();
        }
        // Path algorithm
        // pattern = /controller/method
        // check if we are requesting an api route
        $api = false;
        $festival = false; // checking if it is a festival route or not
        $adminPanel = false; // checking if it is an admin panel route or not
        $home = false; // checking if it is a home route or not
        if (str_starts_with($uri, "api/")) {
            $uri = substr($uri, 4);
            $api = true;
        }else if (str_starts_with($uri, "festival/")) {
            $uri = substr($uri, 9);
            $festival = true;
        }
        else if (str_starts_with($uri, "admin/")) {
            $uri = substr($uri, 6);
            $adminPanel = true;
        }

        // set default controller/method
        $defaultcontroller = 'home';
        $defaultmethod = 'index';

        // ignore query parameters
        $queries = $this->getQueries($uri);
        $uri = $this->stripParameters($uri);

        // read controller/method names from URL
        // explode is similar to split in C#, so it creates an array of strings, so
        // home/about, turns to
        // ["home", "about"]
        $explodedUri = explode('/', $uri);
        // if the first element is null or empty, set it to "home".
        if (!isset($explodedUri[0]) || empty($explodedUri[0])) {
            $explodedUri[0] = $defaultcontroller;
        }
        // . (dot) in php for strings is concatination. Similar to + in C#.
        // $controllerName becomes "homecontroller";
        $controllerName = $explodedUri[0] . "controller";

        if (!isset($explodedUri[1]) || empty($explodedUri[1])) {
            $explodedUri[1] = $defaultmethod;
        }


        // $methodName becomes "about"
        $methodName = $explodedUri[1];

        // $filename becomes ./../controllers/homecontroller.php
        $filename = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if ($api) {
            $filename = __DIR__ . '/../api/controllers/' . $controllerName . '.php';
        }
        else if ($festival) {
            $filename = __DIR__ . '/../controllers/festival/' . $controllerName . '.php';
        }
        else if ($adminPanel) {
            $controllerName='Admin'.$controllerName;
            $filename=__DIR__.'/../controllers/adminPanel/'.$controllerName.'.php'; // makking all admin panel controllers start with Admin
        }

        // check if the $filename exists. Here, the homecontroller.php in the controllers folder.
        if (file_exists($filename)) {
            // require is similar to import in C#.
            require($filename);
            try {
                // create a new instance from the class controllerName.
                // $controllerObj = new $controllerName();
                $controllerObj = new $controllerName;
                if( strcasecmp($explodedUri[0],$defaultcontroller)==0 && strcasecmp($explodedUri[1], $defaultmethod) !== 0){
                    $methodName='loadInfoPages';
                    $queries= $explodedUri[1]; // loading InfoPages
                }
                // call the methodName in the class controllerName
                $controllerObj->{$methodName}($queries);
                // $controllerObj->$methodName();
            } catch(Error $e) {
                // For some reason the class/method doesn't load
                echo "error";
                echo $e;
                http_response_code(500);
                die();
            }
        } else {
            // Controller/method matching the URL not found
            http_response_code(404);
            die();
        }
    }
}