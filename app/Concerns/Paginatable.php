<?php

namespace App\Concerns;

trait Paginatable
{
    /**
     * The maximum number of models to return for pagination.
     *
     * @var int
     */
    protected $pageSizeLimit = 50;

    /**
     * Get the number of models to return per page.
     *
     * @return int
     */
    public function getPerPage(): int
    {
        $pageSize = request('per_page', $this->perPage);

        return min($pageSize, $this->pageSizeLimit);
    }
}
