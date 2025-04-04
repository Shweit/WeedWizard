<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\UserInteractions;
use App\Interface\BlogServiceInterface;
use App\Service\BlogService;
use App\Service\InteractionsType;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BlogController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WeedWizardKernel $weedWizardKernel,
        private readonly BlogServiceInterface $blogService,
        private readonly SerializerInterface $serializer,
    ) {}

    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        $posts = [
            BlogService::FOR_YOU => $this->blogService->getForYouPosts(),
            BlogService::FOLLOWING => $this->blogService->getFollowingPosts(),
        ];

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/blog/search', name: 'weedwizard_blog_search')]
    public function search(Request $request): Response
    {
        $query = $request->query->get('query') ?? '';

        if ($query) {
            $posts = [
                BlogService::TOP_POSTS => $this->blogService->getTopPostsForQuery($query),
                BlogService::LATEST_POSTS => $this->blogService->getLatestPostsForQuery($query),
                BlogService::USERS => $this->blogService->getUsersForQuery($query),
                BlogService::TAGS => $this->blogService->getTagsForQuery($query),
            ];
        }

        return $this->render('blog/search.html.twig', [
            'query' => $query,
            'posts' => $posts ?? [],
        ]);
    }

    #[Route('/blog/user_interactions', name: 'weedwizard_blog_user_interactions')]
    public function user_interactions(): Response
    {
        if (!$this->weedWizardKernel->getUser()) {
            $this->addFlash('error', 'Du musst angemeldet sein, um deine Interaktionen sehen zu können.');

            return $this->redirectToRoute('app_blog');
        }

        $posts = $this->serializer->normalize($this->weedWizardKernel->getUser()->getBlogs(), null, ['groups' => 'user_interactions']); // @phpstan-ignore-line

        foreach ($posts as &$post) {
            $post['user'] = $this->weedWizardKernel->getUser();
            $post['user_interactions'] = [
                'likes' => $this->serializer->normalize( // @phpstan-ignore-line
                    $this->entityManager->getRepository(UserInteractions::class)->findBy([
                        'Post' => $this->entityManager->getRepository(Blog::class)->find($post['id']), // @phpstan-ignore-line
                        'interactionType' => InteractionsType::LIKE,
                    ]),
                    null,
                    ['groups' => 'user_interactions']
                ),
                'comments' => $this->serializer->normalize( // @phpstan-ignore-line
                    $this->entityManager->getRepository(UserInteractions::class)->findBy([
                        'Post' => $this->entityManager->getRepository(Blog::class)->find($post['id']), // @phpstan-ignore-line
                        'interactionType' => InteractionsType::COMMENT,
                    ]),
                    null,
                    ['groups' => 'user_interactions']
                ),
                'views' => $this->serializer->normalize( // @phpstan-ignore-line
                    $this->entityManager->getRepository(UserInteractions::class)->findBy([
                        'Post' => $this->entityManager->getRepository(Blog::class)->find($post['id']), // @phpstan-ignore-line
                        'interactionType' => InteractionsType::VIEW,
                    ]),
                    null,
                    ['groups' => 'user_interactions']
                ),
                'shares' => $this->serializer->normalize( // @phpstan-ignore-line
                    $this->entityManager->getRepository(UserInteractions::class)->findBy([
                        'Post' => $this->entityManager->getRepository(Blog::class)->find($post['id']), // @phpstan-ignore-line
                        'interactionType' => InteractionsType::SHARE,
                    ]),
                    null,
                    ['groups' => 'user_interactions']
                ),
            ];

            $interactionTypes = ['likes', 'comments', 'views', 'shares'];
            foreach ($interactionTypes as $type) {
                $data = $post['user_interactions'][$type];
                $post['user_interactions'][$type]['last30d'] = $this->blogService->getInteractionsForLast30Days($data);
                $post['user_interactions'][$type]['last6m'] = $this->blogService->getInteractionsForLast6Months($data);
                $post['user_interactions'][$type]['sinceBeginning'] = $this->blogService->getInteractionsSinceBeginning($data);

                $post['user_interactions'][$type]['graph'] = $this->blogService->createGraph('Anzahl der ' . $type, $data);
            }
        }
        unset($post);

        return $this->render('blog/user_interactions.html.twig', [
            'posts' => $posts,
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

            preg_match_all('/#(\w+)/', $content, $matches);
            $blog->setTags($matches[1] ?? []);

            if ($parent) {
                $parent = $this->entityManager->getRepository(Blog::class)->find($parent);
                $blog->setParent($parent);

                $userInteraction = new UserInteractions();
                $userInteraction->setUser($user);
                $userInteraction->setPost($parent);
                $userInteraction->setCreatedAt(new \DateTimeImmutable());
                $userInteraction->setInteractionType(InteractionsType::COMMENT);
                $this->entityManager->persist($userInteraction);
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
            ], Response::HTTP_NOT_FOUND);
        }

        $blog->addLike($user);
        $this->entityManager->persist($blog);

        $userInteraction = new UserInteractions();
        $userInteraction->setUser($user);
        $userInteraction->setPost($blog);
        $userInteraction->setCreatedAt(new \DateTimeImmutable());
        $userInteraction->setInteractionType(InteractionsType::LIKE);
        $this->entityManager->persist($userInteraction);

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

        $userInteraction = $this->entityManager->getRepository(UserInteractions::class)->findOneBy([
            'user' => $user,
            'post' => $blog,
            'interactionType' => InteractionsType::LIKE,
        ]);
        $this->entityManager->remove($userInteraction);

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

        if ($this->weedWizardKernel->getUser()) {
            $userInteractions = new UserInteractions();
            $userInteractions->setUser($this->weedWizardKernel->getUser());
            $userInteractions->setPost($blog);
            $userInteractions->setCreatedAt(new \DateTimeImmutable());
            $userInteractions->setInteractionType(InteractionsType::VIEW);
            $this->entityManager->persist($userInteractions);
            $this->entityManager->flush();
        }

        return $this->render('blog/entry.html.twig', [
            'blog' => $blog,
        ]);
    }
}
