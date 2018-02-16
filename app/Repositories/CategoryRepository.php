<?php

namespace App\Repositories;

use App\Category;
use Exception;
use Yish\Generators\Foundation\Repository\Repository;

use function Functional\map;

class CategoryRepository extends Repository
{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function saveCategoriesFromUniqlo($categories)
    {
        map($categories, function ($category) {
            try {
                $model = Category::firstOrNew(['id' => $category->id]);
                
                $model->id = $category->id;
                $model->name = $category->name;
                $model->image = $category->image ?? '';
                $model->level = $category->level;
                $model->weight = $category->weight;
                $model->parent_id = $category->parentId;

                $model->save();
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }

            if ($category->children) {
                $this->saveCategoriesFromUniqlo($category->children);
            }
        });
    }
}