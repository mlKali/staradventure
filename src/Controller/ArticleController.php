<?php

namespace App\Controller;

use App\Entity\Article;
use App\Support\Messages;
use Doctrine\Persistence\ManagerRegistry;
use FOS\CKEditorBundle\Builder\JsonBuilder;
use Symfony\Component\HttpFoundation\Request;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

    #[Route("/show/{articleName}/{page}", name:"show_article", methods:['GET', 'HEAD'])]
    public function showArticle(ManagerRegistry $doctrine, string $articleName, int $page,): Response
    {
        $articleID = $articleName.$page;
        $article = $this->articleRecordExist($doctrine,$articleID);
        
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

    #[Route("/create/{articleName}/{page}" ,name:"create_article", methods:['GET', 'HEAD'])]
    public function createArticle(ManagerRegistry $doctrine,string $articleName, int $page): Response
    {
        $allowedArticles = ['allwin','samuel','isama','isamanh','isamanw','angel','mry','white','terror','hyperion','demoni'];
        $articleID = $articleName.$page;
        
        // We dont want create article if articleID exist or if not in $allowedArticles
        if($this->articleRecordExist($doctrine,$articleID) instanceof Article ||
            !in_array($articleName,$allowedArticles)){
                $message = new Messages();
                $errors = $message->style('warning')->errors("$articleName is not in the list of articles or already exist( $articleID )")->getMessage();
                return new Response((string) $errors, 400);
        }
        
        $entityManager = $doctrine->getManager();

        $article = new Article();
        $article->setArticleName($articleName);
        $article->setArticlePage($page);
        $article->setArticleBody(['article'=>'update this by <a href=/update/'.$articleName.'/'.$page.'>update me :)</a>']);
        $article->setArticleID($articleID);

        $entityManager->persist($article);
        $entityManager->flush();

        return $this->render('article/editor.html.twig',[
            'article' => $article->getArticleBody()
        ]);
    }

    #[Route("/update/{articleName}/{page}" ,name:"update_article", methods:['GET', 'HEAD'])]
    public function updateArticle(Request $request,ManagerRegistry $doctrine,string $articleName, int $page): Response
    {
        $articleID = $articleName.$page;
        $entityManager = $doctrine->getManager();
        
        $article = $this->articleRecordExist($doctrine,$articleID);

        if(!$article instanceof Article){
            $message = new Messages();
            $errors = $message->style('warning')->errors("Article $articleName$page neexistuje")->getMessage();
            return new Response((string) $errors, 400);
        }
       
        $builder = new JsonBuilder(PropertyAccess::createPropertyAccessor());
        $builder
        ->setJsonEncodeOptions(JSON_FORCE_OBJECT)
        ->setValues(array('foo'))
        ->setValue('[1]', 'bar', false)
        ->build();

        $form = $this->createFormBuilder($article)
            ->add('articleID', CKEditorType::class)
            ->add('save', SubmitType::class, ['label' => 'Update Article'])
            ->getForm();

        return $this->renderForm('article/editor.html.twig', [
                'form' => $form,
            ]);        
        
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
