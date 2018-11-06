<?php

namespace App\EventListener;

use App\Entity\Job;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class JobUploadListener
{
    /** @var FileUploader */
    private $uploader;

    /**
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
        $this->fileToString($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->stringToFile($entity);
    }

    /**
     * @param $entity
     */
    private function uploadFile($entity)
    {
        // upload only works for Job entities
        if (!$entity instanceof Job) {
            return;
        }

        $logoFile = $entity->getLogo();

        // only upload new files
        if ($logoFile instanceof UploadedFile) {
            $fileName = $this->uploader->upload($logoFile);

            $entity->setLogo($fileName);
        }
    }

    /**
     * @param $entity
     */
    private function stringToFile($entity)
    {
        if (!$entity instanceof Job) {
            return;
        }

        if ($fileName = $entity->getLogo()) {
            $entity->setLogo(new File($this->uploader->getTargetDirectory() . '/' . $fileName));
        }
    }

    /**
     * @param $entity
     */
    private function fileToString($entity)
    {
        if (!$entity instanceof Job) {
            return;
        }

        $logoFile = $entity->getLogo();

        if ($logoFile instanceof File) {
            $entity->setLogo($logoFile->getFilename());
        }
    }
}
