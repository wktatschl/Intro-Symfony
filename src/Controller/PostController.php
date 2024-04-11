<?php

namespace App\Controller;

use App\DTO\SearchData;
use App\Form\SearchType;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// #[Route('/profile')]


class PostController extends AbstractController
{

    #[Route('/posts', name: 'app_post')]
    // #[IsGranted("is_granted('ROLE_USER')")]
    public function index(PostRepository $postRepo, Request $request, SearchData $searchData): Response
    {
        if($request->query->has('res')){
            // Nous sommes ICI
        }

        $page = $request->query->getInt('page', 1);
        $searchData->page = $page;
        $form = $this->createForm(SearchType::class, $searchData);
        //verifie si il y a une requete
        $form->handleRequest($request);
        $params = [
            "query"=>$searchData->query,
            "categories"=>$searchData->categories,
            "page"=>$page
        ];
        //verifie si le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()) {
            $searchPosts = $postRepo->searchResult();
            return $this->render('post/index.html.twig', [
                'res' => $searchPosts,
                'formView' => $form->createView(),
                "params" => $params,
            ]);
        }else{
            // Afficher tous les posts sans recherche
            $dataPosts = $postRepo->findPublished($page);
            return $this->render('post/index.html.twig', [
                'posts' => $dataPosts,
                'formView' => $form->createView(),
            ]);
        }

    }

    #[Route('/show/{slug}', name: 'show')]
    public function show(string $slug, PostRepository $postRepo): Response
    {
        $post = $postRepo->findOneBy(['slug' => $slug]);
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
