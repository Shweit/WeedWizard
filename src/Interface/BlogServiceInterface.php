<?php

namespace App\Interface;

use Symfony\UX\Chartjs\Model\Chart;

interface BlogServiceInterface
{
    public function getForYouPosts(): array;

    public function getFollowingPosts(): array;

    public function getTopPostsForQuery(string $query): array;

    public function getLatestPostsForQuery(string $query): array;

    public function getUsersForQuery(string $query): array;

    public function getTagsForQuery(string $tag): array;

    public function createGraph(string $title, array $data): Chart;

    public function getInteractionsForLast30Days(array $data): int;

    public function getInteractionsForLast6Months(array $data): int;

    public function getInteractionsSinceBeginning(array $data): int;
}
