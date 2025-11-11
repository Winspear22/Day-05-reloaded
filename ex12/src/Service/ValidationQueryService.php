<?php

namespace App\Service;

use App\DTO\SearchRequest;
use Symfony\Component\HttpFoundation\Request;


class ValidationQueryService
{
    public function validateQueryParams(Request $request): SearchRequest
    {
        $messages = [];

        $filterName = trim($request->query->get('filter_name', ''));
        if (mb_strlen($filterName) > 64)
		{
            $original = $filterName;
            $filterName = mb_substr($filterName, 0, 64);
            $messages[] = ['warning', sprintf('Truncated from %d to 64 chars', mb_strlen($original))];
        }
        if ($filterName && !preg_match('/^[\p{L}\p{N} _\'\-\.,]*$/u', $filterName))
		{
            $messages[] = ['danger', 'Invalid characters in filter.'];
            $filterName = '';
        }

        $allowedSorts = ['name', 'email', 'birthdate'];
        $sortBy = $request->query->get('sort_by', 'name');
        if (!in_array($sortBy, $allowedSorts, true))
		{
            $messages[] = ['danger', 'Invalid sort field. Using default.'];
            $sortBy = 'name';
        }

        $allowedDirs = ['asc', 'desc'];
        $sortDir = $request->query->get('sort_dir', 'asc');
        if (!in_array($sortDir, $allowedDirs, true))
		{
            $messages[] = ['danger', 'Invalid sort direction. Using ascending.'];
            $sortDir = 'asc';
        }

        // Retourner un DTO structuré
        return new SearchRequest(
            filterName: $filterName,
            sortBy: $sortBy,
            sortDirection: $sortDir,
            limit: 100,
            messages: $messages
        );
    }
}

?>