<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/show', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/articles.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route("/show/{articleName}", name: 'article_name')]
    public function articleName(string $articleName): RedirectResponse
    {
        return $this->redirectToRoute('show_article', ['articleName'=>$articleName,'page'=>1]);
    }

    #[Route("/show/{articleName}/{page}", name:"show_article", requirements:['page' => '\d+'], methods:['GET', 'HEAD'])]
    public function showArticle(ManagerRegistry $doctrine, string $articleName, int $page,): Response
    {
        $articleID = $articleName.$page;
        $article = $doctrine->getRepository(Article::class)->findOneBy([
            'ArticleID' => $articleID]);
        
        if(!$article){
            throw $this->createNotFoundException('Something wrong'.$articleName);
        }

        return $this->render('article/story.html.twig',[
            'article' => $article->getArticleBody(),
            'articleName' => $articleName,
            'articlePage' => $page
        ]);
    }

    #[Route("/create/{articleName}/{page}" ,name:"create_article", methods:['GET', 'HEAD'])]
    public function createArticle(ManagerRegistry $doctrine,string $articleName, int $page): Response
    {
        $allowedArticles = ['allwin','samuel','isama','isamanh','isamanw','angel','mry','white','terror','hyperion','demoni'];
        $entityManager = $doctrine->getManager();
        
        if(!in_array($articleName,$allowedArticles)){
            throw $this->createNotFoundException($articleName.' is not in the list of articles');
        }

        return $this->render('article/editor.html,twig',[

        ]);
    }
}
