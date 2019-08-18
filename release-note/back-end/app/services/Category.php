<?php 
namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    public function getAll()
    {
        return $this->categoryRepository->getAll();
    }
}