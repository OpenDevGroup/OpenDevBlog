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
use \OpenDev\Posts;
use \OpenDev\Categories;
use \OpenDev\Util;
use \OpenDev\humanDate;

class Authors extends Model {

    public function getAuthors() {
	return array("id" => 1, "title" => "Author 1");
    }

}
