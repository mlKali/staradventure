<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    #[Route("/show/{articleName}/{page}", name:"show_article", methods:['GET', 'HEAD'])]
    public function showArticle(ManagerRegistry $doctrine, int $page, string $articleName): Response
    {
        
        //$articles = $doctrine->getRepository(Article::class)
        //->find($page);
        //->findAll();
        //->findArticle($articleName);

        //$article = $articles->getArticle();
        $articles = 'somethigng';
        if(!$articles){
            throw $this->createNotFoundException('Something wrong');
        }

        return $this->render('article/index.html.twig',[
            'article' => $articles,
            'article_name' => $articleName         
        ]);
    }
}
