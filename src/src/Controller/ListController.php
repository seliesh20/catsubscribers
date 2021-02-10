<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Subscribers;
use App\Entity\Categories;
use App\Form\SubscriberFormType;

class ListController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(): Response
    {
        return $this->render('list/index.html.twig', [
            
        ]);
    }

    /**
     * @Route("/list/subscribers", name="list_subscribers", methods={"GET", "POST"})
     */
    public function subscribers(Request $request): Response
    {        
        if ($request->getMethod() == 'POST') {
            $subscriber_object = new Subscribers();
            $categories_object = new Categories();

            $search = $request->request->get('search')["value"];
            $sort_column = $request->request->get('columns')[$request->request->get('order')[0]['column']]["data"];            
            $sort_dir =  $request->request->get('order')[0]["dir"];
            $start =  $request->request->get('start');
            $length =  $request->request->get('length');

            $like = $sort = $limit = [];
            if(strlen(trim($search))){
                $like["name"] = $search;
            }
            $sort = ["column"=>$sort_column, "dir"=>$sort_dir];
            $limit = ["start"=> $start, "length"=> $length];            
            $filtered_subscribers = $subscriber_object->getSubscribers($like, $sort, $limit); 
            foreach($filtered_subscribers as $key=>$subscriber){                
                //search
                $subscriber->actions = "<a href='javascript:void(0);' class='btn btn-success subscriber-edit'  data-id='".$subscriber->id."'>Edit</a>";
                $subscriber->actions .= "<a href='javascript:void(0);' class='btn btn-danger subscriber-delete' data-id='".$subscriber->id."'>Delete</a>";
                $categories = [];
                foreach($subscriber->categories as $category){
                    $categories[] = $categories_object->getCategoryById($category);
                }
                $filtered_subscribers[$key]->categories = $categories;                
            }

            return $this->json([
                "draw" => $request->request->get('draw'),
                "recordsTotal" => $subscriber_object->getSubscribersCount(),
                "recordsFiltered" => count($filtered_subscribers),
                "data" => $filtered_subscribers
            ]);
        } else {
            //return new Response($this->json(["status"=>"failure", "message"=>"Invalid Request"]), 200,  ["application/json"]);
            return $this->json(["status"=>"failure", "message"=>"Invalid Request"]);
        }
    }

    /**
     * @Route("/list/deleteSubscriber", name="delete_subscriber", methods={"GET", "POST"})
     */
    public function deleteSubscriber(Request $request):Response
    {
        if ($request->getMethod() == 'POST') {
            $subscriber_object = new Subscribers();
            $id = $request->request->get('id');
            $subscriber_object->deleteSubscriber($id);
            return $this->json(["status"=>"success"]);
        } else {
            //return new Response($this->json(["status"=>"failure", "message"=>"Invalid Request"]), 200,  ["application/json"]);
            return $this->json(["status"=>"failure", "message"=>"Invalid Request"]);
        }

    }
    /**
     * @Route("/list/getSubscriber", name="get_subscriber", methods={"GET", "POST"})
     */
    public function getSubscriber(Request $request):Response
    {
        if ($request->getMethod() == 'POST') {
            $subscriber_object = new Subscribers();
            $id = $request->request->get('id');
            $subscriber = $subscriber_object->getSubscriber(["id"=>$id]);
            return $this->json(["status"=>"success","data"=>$subscriber]);
        } else {
            //return new Response($this->json(["status"=>"failure", "message"=>"Invalid Request"]), 200,  ["application/json"]);
            return $this->json(["status"=>"failure", "message"=>"Invalid Request"]);
        }
    }

    /**
     * @Route("/list/updateSubscriber", name="update_subscriber", methods={"GET", "POST"})
     */
    public function updateSubscriber(Request $request):Response
    {
        if ($request->getMethod() == 'POST') {
            $subscriber_object = new Subscribers();
            $id = $request->request->get('id');
            $subscriber = (array) $subscriber_object->getSubscribers(["id"=>$id]);
            $subscriber['name'] = $request->request->get('name');
            $subscriber['email'] = $request->request->get('email');

            $subscribers_list = (array) $subscriber_object->getSubscribers(["email"=>$request->request->get('email')]);
            if(is_array($subscribers_list) && count($subscribers_list)){
                foreach($subscribers_list as $subscriber_row){
                    if(is_array($subscriber_row) && $email_check->id <> $id){
                        return $this->json(["status"=>"failure", "message"=>"Email Already exists"]);
                    }
                }  
            }
            //update
            $subscriber_object->updateSubscriber($subscriber, $id);
            return $this->json(["status"=>"success"]);            
        } else {
            //return new Response($this->json(["status"=>"failure", "message"=>"Invalid Request"]), 200,  ["application/json"]);
            return $this->json(["status"=>"failure", "message"=>"Invalid Request"]);
        }
    }
    
}
