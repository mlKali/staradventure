<?php 

namespace App\Support;

use App\Entity\Article;
use Symfony\Component\Validator\Constraints as Assert;


class  UpdateArticleRequest{
    
    /**
     * @Assert\NotBlank()
     * @var string
     */
    public $articleName;

    /**
     * @Assert\NotBlank()
     * @var int
     */
    public $articlePage;

    /**
     * @Assert\NotBlank()
     */
    public $articleBody;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    public $articleID;

    public static function fromArticle(Article $article): self
    {
        //Get article from array
        foreach($article->getArticleBody() as $articleItem);

        $articleRequest = new self();
        $articleRequest->articleName = $article->getArticleName();
        $articleRequest->articlePage = $article->getArticlePage();
        $articleRequest->articleBody = $articleItem;
        $articleRequest->articleID = $article->getArticleID();

        return $articleRequest;
    }
}

