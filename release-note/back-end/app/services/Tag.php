<?php 
namespace App\Services;

use App\Repositories\TagRepository;

class TagService
{
    public function __construct()
    {
        $this->tagRepository = new TagRepository();
    }

    public function getAll()
    {
        return $this->tagRepository->getAll();
    }
}