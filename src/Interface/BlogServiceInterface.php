<?php

namespace App\Interface;

interface BlogServiceInterface
{
    public function gerForYouPosts(): string;

    public function getFollowingPosts(): array;

    public function getTopPostsForQuery(string $query): array;

    public function getLatestPostsForQuery(string $query): array;

    public function getUsersForQuery(string $query): array;

    public function getTagsForQuery(string $tag): array;
}
