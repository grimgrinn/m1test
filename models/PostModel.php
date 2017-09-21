<?php



class PostModel {
	 
	 public static function getPostsList(){
		$postsList = array();
        $db = Db::getConnection();

        $result = $db->query('SELECT * FROM posts ORDER BY id DESC');
        $cats = CategoriesModel::getCategoriesList();

        $i = 0;
        while ($row = $result->fetch()) {
            $postsList[$i]['id'] = $row['id'];
            $postsList[$i]['header'] = $row['header'];
            $postsList[$i]['text'] = $row['text'];
            $postsList[$i]['time'] = $row['updated_at'];
            $postsList[$i]['category'] = $row['category_id'];
            $postsList[$i]['image'] = $row['image'];
            $i++;
        }
        return $postsList;
    }

    public static function getPostsByCategory($catId){
		$postsList = array();
        $db = Db::getConnection();

        $sql = 'SELECT * FROM posts WHERE category_id = :catId ORDER BY id DESC';
        $result = $db->prepare($sql);
        $result->bindParam(':catId', $catId, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        $i = 0;
        while ($row = $result->fetch()) {
            $postsList[$i]['id'] = $row['id'];
            $postsList[$i]['header'] = $row['header'];
            $postsList[$i]['text'] = $row['text'];
            $postsList[$i]['time'] = $row['updated_at'];
            $postsList[$i]['category'] = $row['category_id'];
            $postsList[$i]['image'] = $row['image'];
            $i++;
        }
        return $postsList;
    }
	
	 public static function getPostById($id){
    
        $db = Db::getConnection();

        $sql = 'SELECT * FROM posts WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
	
	 public static function addPost($options)
     {

        $db = Db::getConnection();
		var_dump($options);
		if($options['image'])
			$sql = 'INSERT INTO posts (header, text, updated_at, image, category_id) VALUES(:header, :text, :updated_at, :image, :category)';
		else
            $sql = 'INSERT INTO posts (header, text, updated_at, category_id) VALUES(:header, :text, :updated_at, :category)';

        $result = $db->prepare($sql);
        $result->bindValue(':header', $options['header'], PDO::PARAM_STR);
        $result->bindValue(':text', $options['text'], PDO::PARAM_STR);
        $result->bindValue(':category', $options['category'], PDO::PARAM_STR);
        $result->bindValue(':updated_at', time(), PDO::PARAM_STR);
   	    $options['image'] ? $result->bindValue(':image', $options['image'], PDO::PARAM_STR) : true;
        
		if ($result->execute()) {
           return $db->lastInsertId();
        } 
         return 0;
    }
}

