<?php


class CategoriesModel
{

    public static function getCategoriesList()
    {
        $catsList = array();
        $db = Db::getConnection();

        $result = $db->query('SELECT * FROM categories ORDER BY id DESC');

        $i = 0;
        while ($row = $result->fetch()) {
            $catsList[$i]['id'] = $row['id'];
            $catsList[$i]['name'] = $row['name'];
            $i++;
        }
        return $catsList;
    }

    public static function addCategorie($options)
    {
        $db = Db::getConnection();

        $sql = 'INSERT INTO categories (name) VALUES(:name)';

        $result = $db->prepare($sql);
        $result->bindValue(':name', $options['name'], PDO::PARAM_STR);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }




}

