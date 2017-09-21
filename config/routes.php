<?php

return array(
    'post/category/([0-9]+)' => 'post/getByCategory/$1',
    'post/([0-9]+)' => 'post/getById/$1',
    'post/new' => 'post/create',
    'post/add' => 'post/store',

    'categories/add' => 'categories/store',
    'categories' => 'categories/index',
    '' => 'site/index',
);
