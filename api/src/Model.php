<?php
/**
* @author Steve King
* @team Open Dev
* @package Open Dev Blog
* @version 1.0-alpha
* @license GNU
*/

namespace OpenDev;

use PDO;

class Model {
    /**
     * The database connection
     * @var PDO
     */
	private $db;

    /**
     * When creating the model, the configs for database connection creation are needed
     * @param $config
     */
    public function __construct($config) {
        $dsn = 'mysql:host=' . $config['db_host'] . ';dbname='    . $config['db_name'] . ';port=' . $config['db_port'];
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
        $this->db = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
    }

}
