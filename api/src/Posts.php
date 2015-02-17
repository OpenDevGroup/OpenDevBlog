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

class Posts extends Model {

    public function getPosts() {
        return array("id" => 1, "title" => "Post 1");
    }

}
