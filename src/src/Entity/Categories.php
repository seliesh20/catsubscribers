<?php

namespace App\Entity;

class Categories
{
    private $current_dir;
    public function __construct()
    {
        $this->current_dir = dirname(__DIR__);
    }
    public function getList()
    {        
        if(file_exists($this->current_dir.'/../data/categories.json')){
            $categories = json_decode(file_get_contents($this->current_dir.'/../data/categories.json'));
            $list = [];
            foreach($categories as $category){
                $list[$category->name] = $category->id;
            }
            return $list;
        }
        return [];        
    }

    public function getCategoryById(string $id):array
    {
        if(file_exists($this->current_dir.'/../data/categories.json')){
            $categories = json_decode(file_get_contents($this->current_dir.'/../data/categories.json'));
            foreach($categories as $category){
                if($category->id == $id){
                    return (array) $category;
                }
            }            
        }
        return [];        
    }
}