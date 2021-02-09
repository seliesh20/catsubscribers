<?php
//src/Entity/Subscribers.php

namespace App\Entity;

class Subscribers
{
    /**
    * @Assert\NotBlank
    */
    public $name = '';

    /**
    * @Assert\NotBlank
    */
    public $email = '';


    /**
    * @Assert\NotBlank
    */
    public $categories = [];

    /**
     * Save Subscriber
     */
    public function addSubscriber(array $subscriber):void
    {
        $subscribers = $this->getSubscribers();
        $subscriber["id"] = md5(strtotime(date("Y-m-d H:i:s")).rand());
        $subscriber["post_time"] = date("Y-m-d H:i:s");
        $subscribers[] = $subscriber;        
        file_put_contents('../data/subscribers.json', json_encode($subscribers));        
    }
    /**
     * 
     */
    public function deleteSubscriber(string $id):void
    {
        $subscribers = $this->getSubscribers();
        foreach($subscribers as $key=>$subscriber){
            if($subscriber->id == $id){
                $subscribers = array_splice($subscribers, $key, 1);
            }
        }
        file_put_contents('../data/subscribers.json', json_encode($subscribers));        
    }

    /**
     * 
     */
    public function updateSubscriber($subscriber, string $id):void
    {
        $subscribers = $this->getSubscribers();
        foreach($subscribers as $key=>$row){
            if($row->id == $id){                
                $subscribers[$key]->name = $subscriber["name"];
                $subscribers[$key]->email = $subscriber["email"];
            }
        }        
        file_put_contents('../data/subscribers.json', json_encode($subscribers));        
    }

    /**
     * 
     */
    public function getSubscribers($like = [], $sort = [], $limit = []):array
    {
        if(file_exists('../data/subscribers.json')){
            $subscribers = json_decode(file_get_contents('../data/subscribers.json'));
            foreach($subscribers as $k=>$subscriber){
                //like                             
                if(is_array($like) && count($like)){                      
                    foreach($like as $key=>$row){                                                
                        if(strpos(strtolower($subscriber->{$key}), strtolower($row)) !== false){                            
                            $subscribers = array_splice($subscribers, $k, 1);                            
                        }
                    }                    
                }                
            }
            if(is_array($sort) && count($sort)){
                $column = $sort["column"];
                $dir = $sort["dir"];

                usort($subscribers, function($a, $b) use($column, $dir){                                              
                    return strcmp($a->{$column}, $b->{$column}) * ($dir == "asc")?1:-1; 
                });
            }
            
            //limit
            if(is_array($limit) && count($limit)){
                $subscribers = array_slice($subscribers, $limit["start"], $limit["length"]);
            }

            return \is_null($subscribers)?[]:$subscribers;
        }
        return [];
    }
    /**
     * get Subscriber 
     * @param array $conditions Conditions
     */
    public function getSubscriber(array $conditions = []):object
    {
        $subscribers = $this->getSubscribers();
        foreach($subscribers as $subscriber){
            foreach($conditions as $key=>$condition){
                if($subscriber->{$key} == $condition){
                    return $subscriber;
                }
            }
        }
        return new \stdClass();
    }

    /**
     * 
     */
    public function getSubscribersCount():int
    {
        $subscribers = $this->getSubscribers();        
        return is_array($subscribers)?count($subscribers):0;
    }

}