<?php
/**
 * @author Steve King
 * @team Open Dev
 * @package Open Dev Blog
 * @version 1.0-alpha
 * @license GNU
 */

namespace OpenDev;

class Authors {
    /**
     * The database connection
     * @var PDO
     */
	private $db;

    /**
     * [PRIVATE] When creating the model, the configs for database connection creation are needed
     * @param $config
     */
    function __construct($config) {
        // PDO db connection statement preparation
        $dsn = 'mysql:host=' . $config['db_host'] . ';dbname='    . $config['db_name'] . ';port=' . $config['db_port'];

        // note the PDO::FETCH_OBJ, returning object ($result->id) instead of array ($result["id"])
        // @see http://php.net/manual/de/pdo.construct.php
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

        // create new PDO db connection
        $this->db = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
	}
}