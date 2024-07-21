<?php

namespace App\Twig\Runtime;

use App\Entity\Blog;
use App\Entity\BudBash;
use App\Entity\BudBashCheckAttendance;
use App\Entity\Plant;
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

    public function isTaskCompleted(int $plant_id, string $task): int
    {
        $plant = $this->entityManager->getRepository(Plant::class)->find($plant_id);

        $weeklyTasks = $plant->getWeeklyTasks();
        $now = new \DateTime();

        switch ($task) {
            case 'water':
                if (!empty($weeklyTasks['water'])) {
                    $lastWatering = end($weeklyTasks['water']);
                    $lastWateringDate = new \DateTime($lastWatering['date']);
                    $diff = $now->diff($lastWateringDate)->days;

                    return 5 - $diff;
                }

                break;
            case 'fertilize':
                if (!empty($weeklyTasks['fertilize'])) {
                    $lastWatering = end($weeklyTasks['fertilize']);
                    $lastWateringDate = new \DateTime($lastWatering['date']);
                    $diff = $now->diff($lastWateringDate)->days;

                    return 14 - $diff;
                }

                break;
            case 'temperature':
                if (!empty($weeklyTasks['temperature'])) {
                    $lastWatering = end($weeklyTasks['temperature']);
                    $lastWateringDate = new \DateTime($lastWatering['date']);
                    $diff = $now->diff($lastWateringDate)->days;

                    return 2 - $diff;
                }

                break;
            case 'pesticide':
                if (!empty($weeklyTasks['pesticide'])) {
                    $lastWatering = end($weeklyTasks['pesticide']);
                    $lastWateringDate = new \DateTime($lastWatering['date']);
                    $diff = $now->diff($lastWateringDate)->days;

                    return 7 - $diff;
                }

                break;
        }

        return 0;
    }
}
