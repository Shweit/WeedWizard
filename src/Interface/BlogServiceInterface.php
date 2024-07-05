<?php

namespace App\Interface;

interface BlogServiceInterface
{
    public function getForYouPosts(): array;

    public function getFollowingPosts(): array;

    public function getTopPostsForQuery(string $query): array;

    public function getLatestPostsForQuery(string $query): array;

    public function getUsersForQuery(string $query): array;

    public function getTagsForQuery(string $tag): array;
}
