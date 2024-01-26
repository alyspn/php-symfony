<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/editor')]
class EditorController extends AbstractController
{
    #[Route('/editor', name: 'editor_dashboard', methods: ['GET'])]
    public function dashboard(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(
            ['status' => 'pending'], 
            ['publicationDate' => 'DESC']
        );

        return $this->render('editor/editor_dashboard.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}/validate', name: 'editor_validate_article', methods: ['POST'])]
    public function validateArticle(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('validate' . $article->getId(), $request->request->get('_token'))) {
            $article->setStatus('validated');
            $entityManager->persist($article);
            $entityManager->flush();

            
        }

        return $this->redirectToRoute('editor_dashboard');
    }

     #[Route('/article/{id}/archive', name: 'editor_archive_article', methods: ['POST'])]
public function archiveArticle(Request $request, Article $article, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('archive' . $article->getId(), $request->request->get('_token'))) {
        $article->setStatus('archived');
        $entityManager->persist($article);
        $entityManager->flush();

        
    }

    return $this->redirectToRoute('editor_dashboard');
}

    #[Route('/article/{id}/edit', name: 'editor_edit_article', methods: ['GET', 'POST'])]
    public function editArticle(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            
            return $this->redirectToRoute('editor_dashboard');
        }

        return $this->render('editor/editor_edit_article.html.twig', [
            'article' => $article,
            'editForm' => $form->createView(),
        ]);
    }
}
