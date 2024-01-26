<?php
// src/Service/ArticleService.php

namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

class ArticleService
{
    private $entityManager;
    private $articleRepository;

    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
    }

    public function getArchivedArticles()
    {
        // Remplacer avec la logique pour récupérer tous les articles archivés
        return $this->articleRepository->findBy(['status' => 'archived']);
    }

    public function getUnvalidatedArticles()
    {
        // Remplacer avec la logique pour récupérer tous les articles non validés
        return $this->articleRepository->findBy(['status' => 'unvalidated']);
    }

    public function archiveArticle(Article $article)
    {
        // Logique pour archiver un article
        if ($article->getStatus() !== 'archived') {
            $article->setStatus('archived');
            $this->entityManager->flush();
        }
    }

    public function validateArticle($id)
    {
        // Logique pour valider un article par son ID
        $article = $this->articleRepository->find($id);
        if ($article && $article->getStatus() !== 'validated') {
            $article->setStatus('validated');
            $this->entityManager->flush();
            return $article;
        }

        return null;
    }

    // ... autres méthodes utiles ...
}
