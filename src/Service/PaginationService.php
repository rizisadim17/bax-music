<?php

namespace App\Service;

class PaginationService
{
    public function pagination(array $items, int $page, int $perPage): array
    {
        $totalItems = count($items);
        $totalPages = ceil($totalItems / $perPage);
        $offset = ($page - 1) * $perPage;
        $itemsPerPage = array_slice($items, $offset, $perPage);

        return [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_characters' => $totalItems,
            'characters_per_page' => $itemsPerPage,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1,
        ];
    }
}