<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    
    #[Route('/categorie/{id}', name: 'app_categorie')]
    public function index(Categories $categorie, PostRepository $postRepo, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $posts = $postRepo->findPublished($page, $categorie);
        
        return $this->render('categorie/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
