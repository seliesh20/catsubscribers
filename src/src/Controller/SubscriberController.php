<?php

namespace App\Controller;

use App\Form\SubscriberFormType;
use App\Entity\Subscribers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




class SubscriberController extends AbstractController
{
    /**
     * Form for subscriber 
     *
     * @Route("/", name="subscriber")
     */
    public function index(Request $request): Response
    {
        $subscribers = new Subscribers();
        $form = $this->createForm(SubscriberFormType::class, $subscribers);                    

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();
            $subscribers->addSubscriber((array) $task);
            return $this->redirectToRoute('success_subscribe');
        }

        return $this->render("subscriber/form.html.twig", [
           'form' => $form->createView()
        ]);
    }

     /**
     * Form for subscriber 
     *
     * @Route("/success", name="success_subscribe")
     */
    public function success(Request $request): Response
    {
        return $this->render("subscriber/success.html.twig");
    }
}
