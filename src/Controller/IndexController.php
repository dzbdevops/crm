<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted("ROLE_USER")]
class IndexController extends AbstractController
{
    private CompanyRepository $companyRepository;
    public function __construct(CompanyRepository $companyRepository){

        $this->companyRepository = $companyRepository;
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $allCompanies = $this->companyRepository->findAll();
        $myCompanies = $this->companyRepository->findBy(['user' => $this->getUser()]);
        
        return $this->render('index/index.html.twig', [
            'allCompanies' => $allCompanies,
            'myCompanies' => $myCompanies
        ]);
    }
}
