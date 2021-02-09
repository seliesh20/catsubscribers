<?php

namespace App\Entity;

class Categories
{
    public function getList()
    {
        if(file_exists('../data/categories.json')){
            $categories = json_decode(file_get_contents('../data/categories.json'));
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
        if(file_exists('../data/categories.json')){
            $categories = json_decode(file_get_contents('../data/categories.json'));            
            foreach($categories as $category){
                if($category->id == $id){
                    return (array) $category;
                }
            }            
        }
        return [];        
    }
}