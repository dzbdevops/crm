<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\ActivityRepository;

#[IsGranted("ROLE_USER")]
class ActivityDeleteController extends AbstractController
{

    private ActivityRepository $activityRepository;
    public function __construct(ActivityRepository $activityRepository){

        $this->activityRepository = $activityRepository;
    }


    #[Route('/activity/delete/{id}', name: 'app_activity_delete')]
    public function index(int $id): Response    
    {
        $companyid = null;
        $activity = $this->activityRepository->find($id);
        if($activity){
            $companyid = $activity->getCompany()->getId();
            $this->activityRepository->remove($activity, true);
        }
        return $this->redirectToRoute('app_company_detail', ['id' => $companyid]);
       
    }
}
