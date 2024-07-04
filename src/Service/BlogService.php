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

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WeedWizardKernel $weedWizardKernel,
    ) {}

    public function gerForYouPosts(): string
    {
        # TODO Implement this method
        return '';
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
        $posts = $this->entityManager->createQueryBuilder()
            ->select('b')
            ->from(Blog::class, 'b')
            ->orWhere('LOWER(b.content) LIKE :query')
            ->setParameter('query', "%{$query}%")
            ->getQuery()
            ->getResult();

        return $posts;
    }
}
