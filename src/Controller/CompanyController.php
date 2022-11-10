<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;
use App\Form\CompanyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted("ROLE_USER")]
class CompanyController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }

    #[Route('/company', name: 'app_company')]
    public function index(Request $request): Response
    {
        $company = new Company();

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

            $company->setUser($this->getUser());

            $this->entityManager->persist($company);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('company/index.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
