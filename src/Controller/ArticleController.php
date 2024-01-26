<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\Tag;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;


class ArticleController extends AbstractController
{   
   


    #[Route('/', name: 'article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findPublishedBeforeNow(),
        ]);
    }

    #[Route('/article/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $status = $this->isGranted('ROLE_ADMIN') ? 'validated' : 'pending';
            $article->setStatus($status);

            
            $user = $this->getUser(); 
            $article->setAuthor($user);

            
            $tagsInput = $form->get('tagList')->getData(); 
            $tagNames = array_unique(array_filter(array_map('trim', explode(',', $tagsInput))));
            foreach ($tagNames as $tagName) {
                $tag = $entityManager->getRepository(Tag::class)->findOneBy(['name' => $tagName]);
                if (!$tag) {
                    $tag = new Tag();
                    $tag->setName($tagName);
                    $entityManager->persist($tag);
                }
                $article->addTag($tag);
            }

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article created successfully.');

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/article_new.html.twig', [
            'articleForm' => $form->createView(),
        ]);
    }
    

    #[Route('/article/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/article_show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }

  
}
