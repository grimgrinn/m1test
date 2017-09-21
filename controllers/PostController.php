<?php


class PostController {
	
	 public function actionView($postId)
     {
        $post = PostModel::getPostById($postId);

        require_once(ROOT . '/views/post/view.php');
        return true;
    }

    public function actionCreate()    {
        $catsList = CategoriesModel::getCategoriesList();
        require_once(ROOT . '/views/post/create.php');
        return true;
    }
	
	public function actionStore() {
		 $errors = false;	
		if(isset($_POST['header'])){
		 $options['header'] = $_POST['header'];
		 $options['text'] = $_POST['text'];
		 $options['image'] = $_FILES["image"]["header"];
		 $options['category'] = $_POST['category'];

           if (!isset($options['header']) || empty($options['header']) || !isset($options['text']) || empty($options['text'])) {
                $errors[] = 'Заполните поля';
           }
            
            if ($errors == false) {
			
                $id = PostModel::addPost($options);

                if ($id) {
                
                    if (is_uploaded_file($_FILES["image"]["tmp_header"])) {
						try {
							$img = new Picture($_FILES["image"]["tmp_header"]);
							$format = $img->get_original_info()['format'];
							
							if($format == 'jpeg' || $format == 'gif' || $format == 'png') {
								 $img->best_fit(360, 240)->save($_FILES["image"]["tmp_header"]);
							
								move_uploaded_file($_FILES["image"]["tmp_header"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/{$options['image']}");
							} else {
								$errors[] = 'Неверный формат, выберите изображение PNG, GIF или JPEG';
							}
						}catch(Exception $e) {
							$errors[] =  $e->getMessage();
						}
						
                    }
                };
             return true;
            }
	    }
        return true;
	}

    public function actionGetByCategory($catId){
        $posts = PostModel::getPostsByCategory($catId);

        require_once(ROOT . '/views/post/index.php');
        return true;
    }
    public function actionGetById($id){
        $post = PostModel::getPostById($id);

        require_once(ROOT . '/views/post/view.php');
        return true;
    }

}