<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted("ROLE_USER")]
class CompanyDeleteController extends AbstractController
{
    private CompanyRepository $companyRepository;
    public function __construct(CompanyRepository $companyRepository){

        $this->companyRepository = $companyRepository;
    }

    #[Route('/company/delete/{id}', name: 'app_company_delete')]
    public function index(int $id): Response
    {
        $company = $this->companyRepository->find($id);
        if($company){
            $this->companyRepository->remove($company, true);
            $this->addFlash('success', "Pomyślnie usunięto firmę");
        }
        return $this->redirectToRoute('app_index');
    }
}
