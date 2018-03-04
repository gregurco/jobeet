<?php

namespace App\Controller;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class JobController extends AbstractController
{
    /**
     * Lists all job entities.
     *
     * @Route("/", name="job.list")
     * @Method("GET")
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function listAction(EntityManagerInterface $em) : Response
    {
        $query = $em->createQuery(
            'SELECT j FROM App:Job j WHERE j.expiresAt > :date'
        )->setParameter('date', new \DateTime());
        $jobs = $query->getResult();

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("job/{id}", name="job.show", requirements={"id" = "\d+"})
     * @Method("GET")
     *
     * @param Job $job
     *
     * @return Response
     */
    public function showAction(Job $job) : Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }
}
