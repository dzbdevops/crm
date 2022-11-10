<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CustomerRepository;
use App\Repository\CompanyRepository;
use App\Repository\ActivityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Activity;

#[IsGranted("ROLE_USER")]
class CompanyDetailController extends AbstractController
{    
    private CompanyRepository $companyRepository;
    private CustomerRepository $customerRepository;
    private ActivityRepository $activityRepository;
    
    public function __construct(CompanyRepository $companyRepository, CustomerRepository $customerRepository, ActivityRepository $activityRepository){

        $this->companyRepository = $companyRepository;
        $this->customerRepository = $customerRepository;
        $this->activityRepository = $activityRepository;
    }
    
    #[Route('/company/detail/{id}', name: 'app_company_detail')]
    public function index(int $id): Response
    {

        $company = $this->companyRepository->find($id);
        $customers = $this->customerRepository->findBy(["company" => $company->getId()]);
        $activities = $this->activityRepository->findBy(["company" => $company->getId()]);

        return $this->render('company_detail/index.html.twig', [
            'company' => $company,
            'customers' => $customers,
            'activities' => $activities
            
        ]);
    }
}
