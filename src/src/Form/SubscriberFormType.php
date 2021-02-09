<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;

use App\Entity\Categories;
use App\Entity\Subscribers;

class SubscriberFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $categories = new Categories();                       
        $builder
            ->add("name", TextType::class)
            ->add("email", EmailType::class)
            ->add("categories", ChoiceType::class, [
                'choices' => $categories->getList(),
                'multiple' => true               
            ])
            ->add("reset", ResetType::class)
            ->add("save", SubmitType::class)
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function(FormEvent $event){
                    $data = $event->getData();
                    $form = $event->getForm();        
                    $email = $data['email'];

                    $subscribers = new Subscribers();                     
                    if(count((array)$subscribers->getSubscriber(['email'=>$email]))){                        
                        $form->addError(new FormError("User already exists!!"));
                    }
                }
            );
    }
}