<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CompanyRepository;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Activity;
use App\Form\ActivityType;
use DateTime;

class ActivityController extends AbstractController
{
    private CompanyRepository $companyRepository;
    private ActivityRepository $activityRepository;
    public function __construct(CompanyRepository $companyRepository, ActivityRepository $activityRepository)
    {
        $this->companyRepository = $companyRepository;
        $this->activityRepository = $activityRepository;
    }

    #[Route('/activity/company/{id}', name: 'app_activity')]
    public function index(Request $request, int $id): Response
    {
        $company = $this->companyRepository->find($id);

        $activity = new Activity();

        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {            
            $activity->setDescription($form->get("description")->getData());           
            //$activity->setDate(new DateTime());
            $activityDateTime = $form->get("date")->getData();
            $activityDate = DateTime::createFromFormat("d.m.Y", $activityDateTime->format('d.m.Y'));
            $activity->setDate($activityDate);
            $activityNextContactDateTime = $form->get("next_contact")->getData();
            $activityNextContactDate = DateTime::createFromFormat("d.m.Y", $activityNextContactDateTime->format('d.m.Y'));
            $activity->setNextContact($activityNextContactDate);
            foreach($form->get("customer")->getData() as $customer)
            {
                $activity->addCustomer($customer);
            }
            
            $activity->setCompany($form->get("company")->getData());
            $this->activityRepository->save($activity, true);

            return $this->redirectToRoute('app_company_detail', ['id' => $form->get("company")->getData()->getId()]);
        }

        return $this->render('activity/index.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
