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

class Posts extends Model {

    public function getPosts() {
    	$sql = "SELECT post_meta.id, post_meta.title, post_meta.categories, post_meta.published, post_meta.author, post_content.content, post_content.media_id
    			FROM post_meta 
    			LEFT OUTER JOIN post_meta.id ON post_content.id";
    	$query = $this->db->prepare($sql);
    	$query->execute();
    	$allPosts = $query->fetchAll();
    	$postArray = [];
    	foreach($allPosts as $post) {
    		$singular = self::getPost($post->id);
    		array_push($singular, $postArray)
    	}
        return $postArray;
    }

}
