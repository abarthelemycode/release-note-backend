<?php 
namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{

    public function getAll()
    {
        return Category::orderByRaw('name COLLATE NOCASE ASC')->get();
    }

}