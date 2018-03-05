<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
        $categories = $em->getRepository(Category::class)->findWithActiveJobs();

        return $this->render('job/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("job/{id}", name="job.show", requirements={"id" = "\d+"})
     * @Method("GET")
     *
     * @Entity("job", expr="repository.findActiveJob(id)")
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
