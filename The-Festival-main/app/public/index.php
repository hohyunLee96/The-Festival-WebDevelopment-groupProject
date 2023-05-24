<?php

//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);
// require (import) PatternRouter class from the following file:
// __DIR__ represents the current file (index.php) location
require __DIR__ . '/../routers/PatternRouter.php';

// trim removes the unwanted '/'
// $_SERVER is a dictionary containing many things. One of its keys is REQUEST_URI that gives the request uri. For example, /home/about
$uri = trim($_SERVER['REQUEST_URI'], '/');

// defining default avatar image
const DEFAULT_AVATAR = 'blankPerson.png';


session_start();
// create a new instance of PatternRouter.
$router = new PatternRouter();
// call method route on the router object.
$router->route($uri);
