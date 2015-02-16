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