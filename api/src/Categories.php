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
use \OpenDev\Util;
use \OpenDev\humanDate;

class Categories extends Model {

    public function getCategories() {
		$sql = "SELECT id, name from categories";
		$query = $this->db->prepare($sql);
    	$query->execute();
    	return $query->fetchAll();
    }

}
