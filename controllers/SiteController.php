<?php

class SiteController
{

    public function actionIndex(){
  
		
		$posts = PostModel::getPostsList();

        require_once(ROOT . '/views/site/index.php');
        return true;
    }
	
    public function actionSort($sort){
  
		
		$tasks = TaskModel::getTasksListSorted($sort);

        require_once(ROOT . '/views/site/index.php');
        return true;
    }

}
