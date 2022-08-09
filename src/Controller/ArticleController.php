<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\UpdateType;
use App\Support\Messages;
use App\Support\UpdateArticleRequest;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    
    public function __construct(private ManagerRegistry $doctrine, private RequestStack $request){}

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

    #[Route("/show/{articleName}/{page}", name:"show_article", methods:['GET', 'HEAD'])]
    public function showArticle(string $articleName, int $page,): Response
    {
        $articleID = $articleName.$page;
        $article = $this->articleRecordExist($this->doctrine,$articleID);
        
        if(!$article instanceof Article){
            $message = new Messages();
            $errors = $message->style('warning')->errors("Article $articleName$page neexistuje")->getMessage();
            return new Response((string) $errors, 400);
        }

        return $this->render('article/story.html.twig',[
            'article' => $article->getArticleBody(),
            'articleName' => $articleName,
            'articlePage' => $page
        ]);
    }

    //FIXME: should be accessable only by Admin but currently there is no User system
    #[Route("/create/{articleName}/{page}", name:"create_article", methods:['GET', 'HEAD'])]
    public function createArticle(string $articleName, int $page): Response
    {
        $allowedArticles = ['allwin','samuel','isama','isamanh','isamanw','angel','mry','white','terror','hyperion','demoni'];
        $articleID = $articleName.$page;
        
        // We dont want create article if articleID exist or if not in $allowedArticles
        if($this->articleRecordExist($this->doctrine,$articleID) instanceof Article ||
            !in_array($articleName,$allowedArticles)){
                $message = new Messages();
                $errors = $message->style('warning')->errors("$articleName is not in the list of articles or already exist( $articleID )")->getMessage();
                return new Response((string) $errors, 400);
        }
        
        $entityManager = $this->doctrine->getManager();

        $article = new Article();
        $article->setArticleName($articleName);
        $article->setArticlePage($page);
        $article->setArticleBody(['article'=>'update this by <a href=/update/'.$articleName.'/'.$page.'>update me</a>']);
        $article->setArticleID($articleID);

        $entityManager->persist($article);
        $entityManager->flush();

        return $this->render('article/editor.html.twig',[
            'article' => $article->getArticleBody()
        ]);
    }

    //FIXME: should be accessable only by Admin but currently there is no User system
    #[Route("/update/{articleName}/{page}", name:"update_article")]
    public function updateArticle(string $articleName, int $page): Response
    {
        $articleID = $articleName.$page;
        $article = $this->articleRecordExist($this->doctrine,$articleID);

        if(!$article instanceof Article){
            $message = new Messages();
            $errors = $message->style('warning')->errors("Article $articleName$page neexistuje")->getMessage();
            return new Response((string) $errors, 400);
        }

        $updateArticleRequest = UpdateArticleRequest::fromArticle($article);
       
        //Create CkEditor form
        $form = $this->createForm(UpdateType::class, $updateArticleRequest);
        $form->handleRequest($this->request->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            //Get data and manager
            $data = $form->getData();
            $entityManager = $this->doctrine->getManager();

            $article->setArticleName($data->articleName);
            $article->setArticlePage($data->articlePage);
            $article->setArticleBody(['article' => $data->articleBody]);
            $article->setArticleID($data->articleID);
            
            $entityManager->flush();

            return $this->redirectToRoute('show_article', ['articleName'=>$articleName,'page'=>$page]);
        }
    
        return $this->render('article/editor.html.twig', [
                'form' => $form->createView(),
                'viewName' => 'update_article',
                'articleName' => $articleName,
                'articlePage' => $page,
            ]);
    }

    //FIXME: should be accessable only by Admin but currently there is no User system
    #[Route("/delete/{articleName}/{page}",name:"delete_article")]
    public function deleteArticle()
    {
        //TODO: delete only text record 
    }
    
    /**
     * returns Article Entity or false
     *
     * @param  mixed $doctrine
     * @param  string $articleID
     * @return object
     */
    private function articleRecordExist(ManagerRegistry $doctrine,string $articleID)
    {
        $article = $doctrine->getRepository(Article::class)->findOneBy([
            'ArticleID' => $articleID]);
        
        if(!$article){
            return false;
        }
        
        return $article;
    }
}
