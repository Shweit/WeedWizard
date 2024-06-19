<?php

namespace App\Twig\Runtime;

use App\Entity\Blog;
use App\Entity\BudBash;
use App\Entity\BudBashCheckAttendance;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\RuntimeExtensionInterface;

class WeedWizardBudBashLocatorExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function iconForAttendance(BudBash $budBash, User $user): string
    {
        $budBashCheckAttendance = $this->entityManager->getRepository(BudBashCheckAttendance::class)->findOneBy([
            'BudBashParty' => $budBash,
            'participant' => $user,
        ]);

        if (!$budBashCheckAttendance) {
            return '';
        }

        if ($budBashCheckAttendance->isCheckedAttendance()) {
            return '<i class="fa-solid fa-check"></i>';
        }

        return '<i class="fa-solid fa-x"></i>';
    }

    public function secretStringForAttendance(BudBash $budBash, User $user): string
    {
        $budBashCheckAttendance = $this->entityManager->getRepository(BudBashCheckAttendance::class)->findOneBy([
            'BudBashParty' => $budBash,
            'participant' => $user,
        ]);

        return $budBashCheckAttendance->getSecretString();
    }

    public function getUserBlogPosts(User $user, bool $sorted = true): array
    {
        if ($sorted) {
            return $this->entityManager->getRepository(Blog::class)->findBy([
                'user' => $user,
                'parent' => null,
            ], ['createdAt' => 'DESC']);
        }

        return $this->entityManager->getRepository(Blog::class)->findBy([
            'user' => $user,
            'parent' => null,
        ]);
    }

    public function isUserFollowingUser($user, $following): bool
    {
        if ($user == null || $following == null) {
            return false;
        }

        /** @var User $user */
        return $user->getfollowing()->contains($following);
    }

    public function hasUserLikedPost($user, $post): bool
    {
        if ($user == null || $post == null) {
            return false;
        }

        /** @var Blog $post */
        return $post->getLikes()->contains($user);
    }

    public function getAllBlogLikesFromUser(User $user): int
    {
        $blogs = $this->entityManager->getRepository(Blog::class)->findBy([
            'user' => $user,
        ]);

        $likes = 0;
        foreach ($blogs as $blog) {
            $likes += $blog->getLikes()->count();
        }

        return $likes;
    }

    public function arrayKeyFirst(array $array)
    {
        reset($array);

        return key($array);
    }
}
