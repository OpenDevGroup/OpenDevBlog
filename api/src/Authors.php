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

class Authors extends Model {

    public function getAuthors() {
		$sql = "SELECT authors.id. authors.display_name, authors.first_name, authors.last_name, authors_extended.age, authors_extended.job_position
				authors_extended.bio, authors_extended.social_links FROM authors LEFT OUTER JOIN authors.id ON authors_extended.id";
		$query = $this->db->prepare($sql);
    	$query->execute();
    	return $query->fetchAll();
    }

}
