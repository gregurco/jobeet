<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use App\Form\JobType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class JobController extends AbstractController
{
    /**
     * Lists all job entities.
     *
     * @Route("/", name="job.list", methods="GET")
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em) : Response
    {
        $categories = $em->getRepository(Category::class)->findWithActiveJobs();

        return $this->render('job/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("job/{id}", name="job.show", methods="GET", requirements={"id" = "\d+"})
     *
     * @Entity("job", expr="repository.findActiveJob(id)")
     *
     * @param Job $job
     *
     * @return Response
     */
    public function show(Job $job) : Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * Creates a new job entity.
     *
     * @Route("/job/create", name="job.create")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param FileUploader $fileUploader
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, EntityManagerInterface $em, FileUploader $fileUploader) : Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $logoFile */
            $logoFile = $form->get('logo')->getData();

            if ($logoFile instanceof UploadedFile) {
                $fileName = $fileUploader->upload($logoFile);

                $job->setLogo($fileName);
            }

            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute(
                'job.preview',
                ['token' => $job->getToken()]
            );
        }

        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit existing job entity
     *
     * @Route("/job/{token}/edit", name="job.edit", requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function edit(Request $request, Job $job, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute(
                'job.preview',
                ['token' => $job->getToken()]
            );
        }

        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays the preview page for a job entity.
     *
     * @Route("job/{token}", name="job.preview", requirements={"token" = "\w+"})
     *
     * @param Job $job
     *
     * @return Response
     */
    public function preview(Job $job) : Response
    {
        $deleteForm = $this->createDeleteForm($job);
        $publishForm = $this->createPublishForm($job);

        return $this->render('job/show.html.twig', [
            'job' => $job,
            'hasControlAccess' => true,
            'deleteForm' => $deleteForm->createView(),
            'publishForm' => $publishForm->createView(),
        ]);
    }

    /**
     * Delete a job entity.
     *
     * @Route("job/{token}/delete", name="job.delete", requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Request $request, Job $job, EntityManagerInterface $em) : Response
    {
        $form = $this->createDeleteForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($job);
            $em->flush();
        }

        return $this->redirectToRoute('job.list');
    }

    /**
     * Publish a job entity.
     *
     * @Route("job/{token}/publish", name="job.publish", requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function publish(Request $request, Job $job, EntityManagerInterface $em) : Response
    {
        $form = $this->createPublishForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job->setActivated(true);

            $em->flush();

            $this->addFlash('notice', 'Your job was published');
        }

        return $this->redirectToRoute('job.preview', [
            'token' => $job->getToken(),
        ]);
    }

    /**
     * Creates a form to delete a job entity.
     *
     * @param Job $job
     *
     * @return FormInterface
     */
    private function createDeleteForm(Job $job) : FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('job.delete', ['token' => $job->getToken()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Creates a form to publish a job entity.
     *
     * @param Job $job
     *
     * @return FormInterface
     */
    private function createPublishForm(Job $job) : FormInterface
    {
        return $this->createFormBuilder(['token' => $job->getToken()])
            ->setAction($this->generateUrl('job.publish', ['token' => $job->getToken()]))
            ->setMethod('POST')
            ->getForm();
    }
}
