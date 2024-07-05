<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\User;
use App\Entity\UserInteractions;
use App\Service\InteractionsType;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BlogFixtures extends Fixture implements DependentFixtureInterface
{
    private const POSSIBLE_TAGS = [
        'php', 'symfony', 'javascript', 'react', 'vue', 'angular', 'java', 'spring', 'python', 'django', 'ruby', 'rails',
        'weed', 'cannabis', 'marijuana', 'thc', 'cbd', 'indica', 'sativa', 'hybrid', 'edibles', 'concentrates', 'vape',
        'bong', 'pipe', 'joint', 'blunt', 'dab', 'weedwizard', 'weedwizardapp', 'weedwizardapp.com', 'weedwizardapp.net',
    ];
    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        /** @var User $user */
        $user = $this->getReference(UserFixtures::USER_REFERENCE_1);

        $this->generateFollowers($user);
        $this->generateBlogPosts($user, rand(1, 10));
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ClubFixtures::class,
        ];
    }

    private function generateFollowers(User $user): void
    {
        for ($i = 1; $i <= 10; ++$i) {
            $follower = $this->manager->getRepository(User::class)->findOneBy(['username' => 'user-' . $i]);
            $user->addFollower($follower);

            if ($i % 2 === 0) {
                $user->addFollowing($follower);
            }
        }
        $this->manager->persist($user);
        $this->manager->flush();
    }

    private function generateBlogPosts(User $user, int $count): void
    {
        for ($i = 1; $i <= $count; ++$i) {
            $blog = new Blog();
            $blog->setContent('Blog-' . $i);
            $blog->setCreatedAt(new DateTimeImmutable('now - ' . rand(1, 100) . ' days'));

            // Randomly select between 1 and 5 tags, and save them into an array
            // even if only one element is selected, it should be saved as an array
            $tags = [];
            for ($j = 0; $j < rand(1, 5); ++$j) {
                $tags[] = self::POSSIBLE_TAGS[array_rand(self::POSSIBLE_TAGS)];
            }
            $blog->setTags($tags);
            $blog->setUser($user);

            // Add the Tags to the content with a # in front of them
            $blog->setContent($blog->getContent() . ' ' . implode(' ', array_map(fn ($tag) => '#' . $tag, $blog->getTags())));

            $this->manager->persist($blog);

            $this->generateComments($blog, rand(1, 10));
            $this->generateLikes($blog, rand(1, 10));
        }
        $this->manager->flush();
    }

    private function generateComments(Blog $blog, int $count): void
    {
        for ($i = 1; $i <= $count; ++$i) {
            $comment = new Blog();
            $comment->setContent('Comment-' . $i);
            $comment->setCreatedAt(new DateTimeImmutable('now - ' . rand(1, 100) . ' days'));

            // Randomly select between 1 and 5 tags, and save them into an array
            // even if only one element is selected, it should be saved as an array
            $tags = [];
            for ($j = 0; $j < rand(1, 5); ++$j) {
                $tags[] = self::POSSIBLE_TAGS[array_rand(self::POSSIBLE_TAGS)];
            }
            $comment->setTags($tags);

            // Add the Tags to the content with a # in front of them
            $comment->setContent($comment->getContent() . ' ' . implode(' ', array_map(fn ($tag) => '#' . $tag, $comment->getTags())));

            $user = $this->manager->getRepository(User::class)->findOneBy(['username' => 'user-' . $i]);
            $comment->setUser($user);
            $comment->setParent($blog);
            $this->manager->persist($comment);

            $userInteraction = new UserInteractions();
            $userInteraction->setUser($user);
            $userInteraction->setPost($blog);
            $userInteraction->setInteractionType(InteractionsType::COMMENT);

            // Usually, the view would be created at the same time as the comment
            // but for the sake of the fixtures, we will set to a random time
            $userInteraction->setCreatedAt(new DateTimeImmutable('now - ' . rand(0, 6) . ' months'));
            $this->manager->persist($userInteraction);

            $userInteraction = new UserInteractions();
            $userInteraction->setUser($user);
            $userInteraction->setPost($blog);
            $userInteraction->setInteractionType(InteractionsType::VIEW);

            // Usually, the view would be created at the same time as the user viewing the post
            // but for the sake of the fixtures, we will set to a random time
            $userInteraction->setCreatedAt(new DateTimeImmutable('now - ' . rand(0, 6) . ' months'));
            $this->manager->persist($userInteraction);

            $this->manager->flush();
        }
    }

    private function generateLikes(Blog $blog, int $count): void
    {
        for ($i = 1; $i <= $count; ++$i) {
            $liker = $this->manager->getRepository(User::class)->findOneBy(['username' => 'user-' . $i]);
            $blog->addLike($liker);

            $userInteraction = new UserInteractions();
            $userInteraction->setUser($liker);
            $userInteraction->setPost($blog);
            $userInteraction->setInteractionType(InteractionsType::LIKE);

            // Usually, the like would be created at the same time as the user liking the post
            // but for the sake of the fixtures, we will set to a random time
            $userInteraction->setCreatedAt(new DateTimeImmutable('now - ' . rand(0, 6) . ' months'));
            $this->manager->persist($userInteraction);

            $this->manager->flush();
        }
    }
}
