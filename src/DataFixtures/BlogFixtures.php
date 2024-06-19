<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BlogFixtures extends Fixture implements DependentFixtureInterface
{
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
            $blog->setUser($user);

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

            $user = $this->manager->getRepository(User::class)->findOneBy(['username' => 'user-' . $i]);
            $comment->setUser($user);
            $comment->setParent($blog);

            $this->manager->persist($comment);
        }
    }

    private function generateLikes(Blog $blog, int $count): void
    {
        for ($i = 1; $i <= $count; ++$i) {
            $liker = $this->manager->getRepository(User::class)->findOneBy(['username' => 'user-' . $i]);
            $blog->addLike($liker);
        }
    }
}
