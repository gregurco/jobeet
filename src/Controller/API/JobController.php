<?php

namespace App\Controller\API;

use App\Entity\Affiliate;
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
     * @Rest\Get("/{token}/jobs", name="api.job.list")
     *
     * @param string $token
     *
     * @return Response
     */
    public function getJobsAction(string $token)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Affiliate $affiliate */
        $affiliate = $em->getRepository(Affiliate::class)->findOneByToken($token);

        if (!$affiliate || !$affiliate->isActive()) {
            throw $this->createNotFoundException();
        }

        $jobs = $em->getRepository(Job::class)->findActiveJobsForAffiliate($affiliate);

        return $this->handleView($this->view($jobs, Response::HTTP_OK));
    }
}
