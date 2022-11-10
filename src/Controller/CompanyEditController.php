<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CompanyRepository;
use App\Form\CompanyType;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted("ROLE_USER")]
class CompanyEditController extends AbstractController
{
    private CompanyRepository $companyRepository;
    public function __construct(CompanyRepository $companyRepository){

        $this->companyRepository = $companyRepository;
    }

    #[Route('/company/edit/{id}', name: 'app_company_edit')]
    public function index(int $id, Request $request): Response    
    {
        $company = $this->companyRepository->find($id);
        if($company){
            $form = $this->createForm(CompanyType::class, $company);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {            
                $company->setName($form->get("name")->getData());
                $companyDateTime = $form->get("last_call_date")->getData();
                $companyDate = DateTime::createFromFormat("d.m.Y", $companyDateTime->format('d.m.Y'));
                $company->setLastCallDate($companyDate);
                $company->setStatus($form->get("status")->getData());
                $company->setDescription($form->get("description")->getData());
                $company->setStreet($form->get("street")->getData());
                $company->setZipCode($form->get("zip_code")->getData());
                $company->setCity($form->get("city")->getData());
                $company->setPhone($form->get("phone")->getData());
                $company->setWebsite($form->get("website")->getData());       

                $this->companyRepository->save($company, true);   

                return $this->redirectToRoute('app_company_detail', ['id' =>$company->getId()]);
            }


            return $this->render('company_edit/index.html.twig', [
                'form' =>$form->createView()                
            ]);
        }        
        
    }
}
