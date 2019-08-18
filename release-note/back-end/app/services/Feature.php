<?php 
namespace App\Services;

use App\Repositories\FeatureRepository;

class FeatureService
{

    public function __construct()
    {
        $this->featureRepository = new FeatureRepository();
    }

    public function getAll()
    {
        return $this->featureRepository->getAll();
    }

    public function getAllByVersion($version1, $version2)
    {
        return $this->featureRepository->getAllByVersion($version1, $version2);
    }

    public function getOne($id)
    {
        return $this->featureRepository->getOne($id);
    }

    public function create($data)
    {       
        return $this->featureRepository->create($data);
    }

    public function update($id, $data)
    {       
        return $this->featureRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->featureRepository->delete($id);
    }
}