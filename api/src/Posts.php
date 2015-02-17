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
use \OpenDev\Authors;
use \OpenDev\Categories;
use \OpenDev\Util;
use \OpenDev\humanDate;

class Posts extends Model {

    public function getPosts() {
    	$sql = "SELECT post_meta.id, post_meta.title, post_meta.categories, post_meta.published, post_meta.author, post_content.content, post_content.media_id
    			FROM post_meta 
    			LEFT OUTTER JOIN post_meta.id ON post_content.id";
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

    public function getPost($id) {
    	$sql = "SELECT post_meta.title, post_meta.categories, post_meta.published post_content.content, post_content.media_id
    			FROM post_meta LEFT OUTTER JOIN post_meta.id ON post_content.id
    			WHERE post_meta.id = :id LIMIT 1";
    	$query = $this->db->prepare($sql);
    	$parameters = [":id" => $id];
    	$query->execute($parameters;
    	return $query->fetch();
    }

}
