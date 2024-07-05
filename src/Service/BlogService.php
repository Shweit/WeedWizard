<?php

namespace App\Service;

use App\Entity\Blog;
use App\Entity\User;
use App\Interface\BlogServiceInterface;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;

class BlogService implements BlogServiceInterface
{
    public const FOR_YOU = 'for_you';
    public const FOLLOWING = 'following';

    public const TOP_POSTS = 'top';
    public const LATEST_POSTS = 'latest';
    public const USERS = 'users';
    public const TAGS = 'tags';

    public const INTERACTION_WEIGHT = [
        'view' => 1,
        'like' => 7,
        'comment' => 5,
        'share' => 3,
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WeedWizardKernel $weedWizardKernel,
    ) {}

    public function getForYouPosts(): array
    {
        $user = $this->weedWizardKernel->getUser();

        if (!$user) {
            // Add some random posts if there are no tags
            $posts = $this->entityManager->getRepository(Blog::class)->findAll();
            shuffle($posts);

            return array_slice($posts, 0, 100);
        }

        $interactions = $user->getUserInteractions();
        $tagWeights = [];

        // Calculate the weight of each tag based on the interactions
        foreach ($interactions as $interaction) {
            $tags = $interaction->getPost()->getTags();
            foreach ($tags as $tag) {
                if (!isset($tagWeights[$tag])) {
                    $tagWeights[$tag] = 0;
                }

                $interactionType = $interaction->getInteractionType();
                $weight = self::INTERACTION_WEIGHT[$interactionType->value] ?? 0;
                $tagWeights[$tag] += $weight;
            }
        }

        // Sort the tags based on the weight
        arsort($tagWeights);

        // Get posts based on the tags
        if (!empty($tagWeights)) {
            $posts = [];
            foreach ($tagWeights as $tag => $weight) {
                $posts[] = $this->getTagsForQuery($tag);
            }

            // Merge the posts into a single array
            $singleArray = [];
            foreach ($posts as $post) {
                foreach ($post as $p) {
                    $singleArray[] = $p;
                }
            }

            // Remove double entries in the array of entities
            $uniquePosts = new \SplObjectStorage();
            foreach ($singleArray as $post) {
                $uniquePosts->attach($post);
            }

            // Convert SplObjectStorage back to array
            $uniqueArray = [];
            foreach ($uniquePosts as $post) {
                $uniqueArray[] = $post;
            }

            // Remove all the posts that the user has already interacted with
            foreach ($interactions as $interaction) {
                $uniqueArray = array_filter($uniqueArray, function (Blog $post) use ($interaction) {
                    return $post->getId() !== $interaction->getPost()->getId();
                });
            }

            // Add some random posts and limit the result to 100
            $randomPosts = $this->entityManager->getRepository(Blog::class)->findAll();
            shuffle($randomPosts);
            $randomPosts = array_slice($randomPosts, 0, 100);

            // Merge the unique posts with the random posts
            return array_merge($uniqueArray, $randomPosts);
        }

        // Add some random posts if there are no tags
        $posts = $this->entityManager->getRepository(Blog::class)->findAll();
        shuffle($posts);

        return array_slice($posts, 0, 100);
    }

    public function getFollowingPosts(): array
    {
        if (!$this->weedWizardKernel->getUser()) {
            return [];
        }

        $posts = [];
        foreach ($this->weedWizardKernel->getUser()->getfollowing() as $following) {
            $posts[] = $following->getBlogs()->toArray();
        }

        $singleArray = [];
        foreach ($posts as $post) {
            foreach ($post as $p) {
                $singleArray[] = $p;
            }
        }

        // Sort the result based on the creation date
        usort($singleArray, function (Blog $a, Blog $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });

        return $singleArray;
    }

    public function getTopPostsForQuery(string $query): array
    {
        $query = strtolower($query);
        $query = str_replace(' ', '', $query);

        $posts = $this->entityManager->createQueryBuilder()
            ->select('b')
            ->from(Blog::class, 'b')
            ->orWhere('LOWER(b.content) LIKE :query')
            ->setParameter('query', "%{$query}%")
            ->getQuery()
            ->getResult();

        // Sort the result based on the number of likes
        usort($posts, function (Blog $a, Blog $b) {
            return count($b->getLikes()) <=> count($a->getLikes());
        });

        return $posts;
    }

    public function getLatestPostsForQuery(string $query): array
    {
        $query = strtolower($query);
        $query = str_replace(' ', '', $query);

        return $this->entityManager->createQueryBuilder()
            ->select('b')
            ->from(Blog::class, 'b')
            ->orWhere('LOWER(b.content) LIKE :query')
            ->setParameter('query', "%{$query}%")
            ->orderBy('b.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getUsersForQuery(string $query): array
    {
        $query = strtolower($query);
        $query = str_replace(' ', '', $query);

        return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->orWhere('LOWER(u.username) LIKE :query')
            ->orWhere('LOWER(u.email) LIKE :query')
            ->setParameter('query', "%{$query}%")
            ->getQuery()
            ->getResult();
    }

    public function getTagsForQuery(string $tag): array
    {
        $query = strtolower($tag);
        $query = str_replace(' ', '', $query);

        // If the tag comes without an #, add it
        if (strpos($query, '#') === false) {
            $query = '#' . $query;
        }

        // Now get all Entries that contain the tag
        return $this->entityManager->createQueryBuilder()
            ->select('b')
            ->from(Blog::class, 'b')
            ->orWhere('LOWER(b.content) LIKE :query')
            ->setParameter('query', "%{$query}%")
            ->getQuery()
            ->getResult();
    }
}
