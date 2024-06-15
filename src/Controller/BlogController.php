<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WeedWizardKernel $weedWizardKernel,
    ) {
    }

    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    #[Route('/blog/add', name: 'app_blog_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $content = $data['content'] ?? null;
        $parent = $data['parent'] ?? null;

        $user = $this->weedWizardKernel->getUser();
        if (!$user) {
            return new JsonResponse([
                'error' => 'Du musst angemeldet sein, um einen Beitrag schreiben zu können.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$content) {
            return new JsonResponse([
                'error' => 'Der Inhalt deines Beitrages darf nicht leer sein.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $blog = new Blog();
            $blog->setUser($user);
            $blog->setContent($content);
            $blog->setCreatedAt(new \DateTimeImmutable());

            if ($parent) {
                $parent = $this->entityManager->getRepository(Blog::class)->find($parent);
                $blog->setParent($parent);
            }

            $this->entityManager->persist($blog);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Es ist ein unbekannter Fehler aufgetreten. Bitte versuche es später erneut.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'success' => 'Dein Beitrag wurde erfolgreich erstellt.',
        ], Response::HTTP_CREATED);
    }

    #[Route('/blog/like/{id}', name: 'app_blog_like', methods: ['POST'])]
    public function like(Request $request, int $id): JsonResponse
    {
        $user = $this->weedWizardKernel->getUser();
        if (!$user) {
            return new JsonResponse([
                'error' => 'Du musst angemeldet sein, um einen Beitrag liken zu können.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $blog = $this->entityManager->getRepository(Blog::class)->find($id);
        if (!$blog) {
            return new JsonResponse([
                'error' => 'Der Beitrag konnte nicht gefunden werden.',
                'likes' => $blog->getLikes()->count(),
            ], Response::HTTP_NOT_FOUND);
        }

        $blog->addLike($user);

        $this->entityManager->persist($blog);
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => 'Der Beitrag wurde erfolgreich geliked.',
            'likes' => $blog->getLikes()->count(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/blog/unlike/{id}', name: 'app_blog_unlike', methods: ['POST'])]
    public function unlike(Request $request, int $id): JsonResponse
    {
        $user = $this->weedWizardKernel->getUser();
        if (!$user) {
            return new JsonResponse([
                'error' => 'Du musst angemeldet sein, um einen Beitrag unliken zu können.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $blog = $this->entityManager->getRepository(Blog::class)->find($id);
        if (!$blog) {
            return new JsonResponse([
                'error' => 'Der Beitrag konnte nicht gefunden werden.',
            ], Response::HTTP_NOT_FOUND);
        }

        $blog->removeLike($user);

        $this->entityManager->persist($blog);
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => 'Der Beitrag wurde erfolgreich ungeliked.',
            'likes' => $blog->getLikes()->count(),
        ], Response::HTTP_CREATED);
    }



    #[Route('/blog/{id}', name: 'app_blog_entry')]
    public function show(int $id): Response
    {
        $blog = $this->entityManager->getRepository(Blog::class)->find($id);

        return $this->render('blog/entry.html.twig', [
            'blog' => $blog,
        ]);
    }
}
