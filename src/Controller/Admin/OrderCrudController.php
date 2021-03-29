<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\User;
use App\Classe\Mail;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

use Doctrine\ORM\EntityManagerInterface;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private $crudUrlGenerator;
    public function __construct(EntityManagerInterface $entityManager,CrudUrlGenerator $crudUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->crudUrlGenerator=$crudUrlGenerator;
    }


    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation=Action::new('updatePreparation','Préparation en cours','fas fa-box-open')->linkToCrudAction('updatePreparation');
        $updateDelivery=Action::new('updateDelivery','Livraison  en cours','fas fa-truck')->linkToCrudAction('updateDelivery');

        
        return $actions
            ->add('detail',$updatePreparation)
            ->add('detail',$updateDelivery)
            ->add('index','detail');
    }

    public function updatePreparation(AdminContext $context)
    {
        $order=$context->getentity()->getInstance();
        $order->setState(2);
        $this->entityManager->flush();

        $this->addFlash('notice',"<span style='color:green;'><strong>La commande ".$order->getReference()." est bien <u>en cour de préparation</u> </strong></span>");

        $url=$this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

            $mail=new Mail();
            $content="Bonjour ".$order->getUser()->getFirstname()."</br> Votre commande est en cour de préparation</br></br>";
            $mail->send($order->getUser()->getEmail(),$order->getUser()->getFirstname(),'Votre commande est en cour de préparation',$content);
            
        return $this->redirect($url);
    }


    public function updateDelivery(AdminContext $context)
    {
        $order=$context->getentity()->getInstance();
        $order->setState(3);
        $this->entityManager->flush();

        $this->addFlash('notice',"<span style='color:orange;'><strong>La commande ".$order->getReference()." est bien <u>en cour de livraison</u> </strong></span>");

        $url=$this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();


            $mail=new Mail();
            $content="Bonjour ".$order->getUser()->getFirstname()."</br> Votre commande est en cour de livraison</br></br>";
            $mail->send($order->getUser()->getEmail(),$order->getUser()->getFirstname(),'Votre commande est en cour de livraison',$content);
        return $this->redirect($url);
    }

    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id'=>'DESC']);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt','Passée le'),
            TextField::new('user.getFullName','Utilisateur'),
            TextEditorField::new('delivery','Adresse de livraison')->onlyOnDetail(),
            TextField::new('carrierName','transporteur'),
            MoneyField::new('total','Total produit')->setCurrency('EUR'),
            MoneyField::new('carrierPrice','Frais de port')->setCurrency('EUR'),
            ArrayField::new('orderDetails','Produits achetés')->hideOnIndex(),
            ChoiceField::new('state')->setChoices([
                'Non payée'=>0,
                'Payée'=>1,
                'Préparation en cour'=>2,
                'Livraison en cour'=>3
            ]),
        ];
    }
    
}
