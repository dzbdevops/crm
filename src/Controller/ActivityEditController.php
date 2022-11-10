<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CompanyRepository;
use App\Repository\ActivityRepository;
use App\Form\ActivityType;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted("ROLE_USER")]
class ActivityEditController extends AbstractController
{
    private ActivityRepository $ativityRepository;
    private CompanyRepository $companyRepository;
    public function __construct(ActivityRepository $activityRepository, CompanyRepository $companyRepository){
        
        $this->activityRepository = $activityRepository;
        $this->companyRepository = $companyRepository;
    }

    #[Route('/activity/edit/{id}', name: 'app_activity_edit')]
    public function index(int $id, Request $request): Response
    {

        $activity = $this->activityRepository->find($id);
        if($activity){
            $form = $this->createForm(ActivityType::class, $activity);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {            
                $activity->setDescription($form->get("description")->getData());
                $activityDateTime = $form->get("date")->getData();
                $activityDate = DateTime::createFromFormat("d.m.Y", $activityDateTime->format('d.m.Y'));
                $activity->setDate($activityDate);

                $activityDateTime = $form->get("next_contact")->getData();
                $activityDate = DateTime::createFromFormat("d.m.Y", $activityDateTime->format('d.m.Y'));
                $activity->setDate($activityDate);
                
                foreach ($form->get("customer")->getData() as $customer){
                    $activity->addCustomer($customer);
                }
                
                $activity->setCompany($form->get("company")->getData());

                $this->activityRepository->save($activity, true);   

                return $this->redirectToRoute('app_company_detail', ['id'=> $activity->getCompany()->getId()]);
            }
        }

        return $this->render('activity_edit/index.html.twig', [
            'form' =>$form->createView()        
        ]);
    }
}
