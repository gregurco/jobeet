<?php

namespace App\Controller\API;

use App\Entity\Job;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\Route("api")
 */
class JobController extends FOSRestController
{
    /**
     * @Rest\Get("/jobs", name="api.job.list")
     */
    public function getJobsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $jobs = $em->getRepository(Job::class)->findActiveJobs();

        return $this->handleView($this->view($jobs, Response::HTTP_OK));
    }
}
