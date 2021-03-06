<?php
/**
* @author Steve King
* @team Open Dev
* @package Open Dev Blog
* @version 1.0-alpha
* @license GNU
*/

error_reporting(E_ALL);
ini_set("display_errors", 1);

require "vendor/autoload.php";

$app = new \Slim\Slim();
$app->view = new \Slim\Views\Twig();
$app->view->setTemplatesDirectory("src/Views/");
$app->configureMode("development", function () use ($app) {
    $app->config(array(
        "debug" => true,
        "database" => array(
            "db_host" => "localhost",
            "db_port" => "",
            "db_name" => "opendev",
            "db_user" => "opendev_admin",
            "db_pass" => "deGrasseTyson1234567890"
        )
    ));
});

$app->configureMode("production", function () use ($app) {
    $app->config(array(
        "debug" => false,
        "database" => array(
            "db_host" => "",
            "db_port" => "",
            "db_name" => "",
            "db_user" => "",
            "db_pass" => ""
        )
    ));
});

$posts = new \OpenDev\Posts($app->config("database"));
$categories = new \OpenDev\Categories($app->config("database"));
$authors = new \OpenDev\Authors($app->config("database"));

$app->notfound(function () use($app) {
    echo "404 Not Found";
});

$app->group('/v1', function () use($app, $posts, $categories, $authors) {
    $app->contentType('application/json');

    $app->get('/', function () use($app) {
	    echo "API Base";
    });
    
    $app->group('/posts', function () use($app, $posts) {
        $app->get('/', function () use($app, $posts) {
	        echo json_encode($posts->getPosts(), JSON_PRETTY_PRINT);
        });
    });

    $app->group('/categories', function () use($app, $categories) {
	    $app->get('/', function () use($app, $categories) {
	        echo json_encode($categories->getCategories(), JSON_PRETTY_PRINT);
        });
    });

    $app->group('/authors', function () use($app, $authors) {
	    $app->get('/', function () use($app, $authors) {
	        echo json_encode($authors->getAuthors(), JSON_PRETTY_PRINT);
        });
    });

});


/******************************************* RUN THE APP *******************************************************/

$app->run();


