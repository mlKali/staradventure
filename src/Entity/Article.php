<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $articleName = null;

    #[ORM\Column]
    private ?int $articlePage = null;

    #[ORM\Column(type: 'json_document', options: ['jsonb' => true])]
    private array $articleBody = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleName(): ?string
    {
        return $this->articleName;
    }

    public function setArticleName(string $articleName): self
    {
        $this->articleName = $articleName;

        return $this;
    }

    public function getArticlePage(): ?int
    {
        return $this->articlePage;
    }

    public function setArticlePage(int $articlePage): self
    {
        $this->articlePage = $articlePage;

        return $this;
    }

    public function getArticleBody(): array
    {
        return $this->articleBody;
    }

    public function setArticleBody(?array $articleBody): self
    {
        $this->articleBody = $articleBody;

        return $this;
    }
}