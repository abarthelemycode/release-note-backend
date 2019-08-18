<?php 
namespace App\Repositories;

use App\Models\Feature;

class FeatureRepository
{
    public function getAll()
    {
        return Feature::with('tags','category')->get();
    }

    public function getAllByVersion($versionFrom, $versionTo)
    {
        return Feature::with('tags','category')->whereBetween('version', [$versionFrom, $versionTo])->orderByRaw('version COLLATE NOCASE DESC')->get();
    }

    public function getOne($id)
    {
        return Feature::with('tags','category')->find($id);
    }

    public function create($data)
    {        
        $feature = new Feature();
        $feature->name = $data->name;
        $feature->text = $data->text;
        $feature->link = $data->link;
        $feature->is_image = $data->is_image;
        $feature->version = $data->version;
        $feature->category_id = $data->category['id'];
        $feature->order = $data->order;
        $feature->save();

        //add tags
        $tagIds = [];
        foreach ($data->tags as $tag) 
             array_push($tagIds, $tag['id']);

        $feature->tags()->sync($tagIds);
        $feature->load('tags', 'category');
        return $feature;
    }

    public function update($id, $data)
    {
        $feature = Feature::find($id);
        // if entity not found, return null
        if($feature === null)
            return null; 
        
        $feature->name = $data->name;
        $feature->text = $data->text;
        $feature->link = $data->link;
        $feature->is_image = $data->is_image;
        $feature->version = $data->version;
        $feature->category_id = $data->category['id'];
        $feature->order = $data->order;
        $feature->save();

        //update tags
        $tagIds = [];
        foreach ($data->tags as $tag) 
            array_push($tagIds, $tag['id']);

        $feature->tags()->sync($tagIds);
        $feature->load('tags', 'category');
        return $feature;
    }

    public function delete($id)
    {
        $feature = Feature::find($id);
        // if entity not found, return null
        if($feature === null)
             return null; 
        
        $feature->tags()->detach();
        $feature->delete();

        return $feature;
    }
}