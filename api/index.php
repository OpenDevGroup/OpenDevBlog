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

$app->group('/v1', function () use($app) {
	
	$app->contentType('application/json');

	$app->group('/posts', function () use($app) {
		$app->get('/', function () use($app) {
			// Get all of the posts from the Model
		});
		$app->get('/:id', function () use($app) {
			// Get a single Post
		})
	});

	$app->group('/categories', function () use($app) {
		$app->get('/', function () use($app) {
			// Get all of the Categories
		});
		$app->get('/:id', function () use($app) {
			// Get a single Category
		});
	});

	$app->group('/authors', function () use($app) {
		$app->get('/', function () use($app) {
			// Get all Authors
		});
		$app->get('/:id', function () use($app) {
			// Get a single Author
		})
	});

});

$app->notfound(function () use($app) {
	echo "404 Page"''
});

/* AND DEPLOY */
$app->run();