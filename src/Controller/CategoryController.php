<?php

namespace App\Controller;

use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    /**
     * Finds and displays a category entity.
     *
     * @Route("/category/{slug}", name="category.show")
     * @Method("GET")
     *
     * @param Category $category
     *
     * @return Response
     */
    public function showAction(Category $category) : Response
    {
        return $this->render('category/show.html.twig', array(
            'category' => $category,
        ));
    }
}
