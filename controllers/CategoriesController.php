<?php

class CategoriesController
{
    public function actionIndex()
    {
        $catsList = CategoriesModel::getCategoriesList();

        require_once(ROOT . '/views/categories/index.php');
        return true;

    }

    public function actionStore()
    {
        $errors = false;

        if (isset($_POST['name'])) {
            $options['name'] = $_POST['name'];

            if (!isset($options['name'])) {
                $errors[] = 'Заполните поля';
            }

            if ($errors == false) {
                $id = CategoriesModel::addCategorie($options);
                echo json_encode(['success' => true, 'id' => $id]);
                return true;
            }
        }

        return $errors;
    }
}