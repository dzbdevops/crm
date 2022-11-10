<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CustomerRepository;
use App\Repository\CompanyRepository;
use App\Entity\Customer;
use App\Form\CustomerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted("ROLE_USER")]
class CustomerController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }        
    
    #[Route('/customer', name: 'app_customer')]
    public function index(Request $request): Response
    {
       
        $customer = new Customer();

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {            
            $customer->setName($form->get("name")->getData());           
            $customer->setSurname($form->get("surname")->getData());
            $customer->setPhone($form->get("phone")->getData());
            $customer->setEmail($form->get("email")->getData());
            $customer->setCompany($form->get("company")->getData());

            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_company_detail', ['id' => $form->get("company")->getData()->getId()]);
        }
        
            return $this->render('customer/index.html.twig', [
                "form" => $form->createView()
            ]);
    }
}
   
    


    
