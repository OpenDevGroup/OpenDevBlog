<?php
/**
 * @author Steve King
 * @team Open Dev
 * @package Open Dev Blog
 * @version 1.0-alpha
 * @license GNU
 */

require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->view = new \Slim\Views\Twig();
$app->view->setTemplatesDirectory('src/views/');
$app->configureMode('development', function () use($app) {
	$app->config(array(
		'debug' => true,
		'database' => array(
			'db_host' => 'localhost',
			'db_port' => '',
			'db_name' => 'table name',
			'db_user' => 'db user',
			'db_pass' => 'db pass'
		))
	);
});

$app->get('/', function () use($app) {
	$app->redirect('/v1', 301);
});

$app->notfound(function () use($app) {
	$app->render('404.twig');
});

$app->group('/v1', function () use($app) {

	$app->contentType('application/json');

	$posts = new \OpenDev\Posts($app->config("database"));
	$authors = new \OpenDev\Authors($app->config("database"));
	$categories = new \OpenDev\Categories($app->config("database"));

	$app->group('/posts', function () use($app, $posts) {
		$app->get('/', function () use($app, $posts) {
			// Get all of the posts from the Model
			// echo json_encode($result, JSON_PRETTY_PRINT);
		});
		$app->get('/:id', function () use($app, $posts) {
			// Get a single Post
			// echo json_encode($result, JSON_PRETTY_PRINT);
		})
	});

	$app->group('/categories', function () use($app, $categories) {
		$app->get('/', function () use($app, $categories) {
			// Get all of the Categories
			// echo json_encode($result, JSON_PRETTY_PRINT);
		});
		$app->get('/:id', function () use($app, $categories) {
			// Get a single Category
			// echo json_encode($result, JSON_PRETTY_PRINT);
		});
	});

	$app->group('/authors', function () use($app, $authors) {
		$app->get('/', function () use($app, $authors) {
			// Get all Authors
			// echo json_encode($result, JSON_PRETTY_PRINT);
		});
		$app->get('/:id', function () use($app, $authors) {
			// Get a single Author
			// echo json_encode($result, JSON_PRETTY_PRINT);
		})
	});
});

$app->group('/admin', function () use($app) {
	// This is the main admin area for the blog.
});


/* AND DEPLOY */
$app->run();