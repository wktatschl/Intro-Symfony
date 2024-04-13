<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    #[Route('/tag/{id}', name: 'app_tag')]
    public function index(Tag $tag, PostRepository $postRepo, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
  
        $posts = $postRepo->findPublished($page, null, $tag);

        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
            'tags' => $posts,
            'tag' => $tag
        ]);
    }
}
