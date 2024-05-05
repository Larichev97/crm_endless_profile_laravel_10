<?php

namespace App\Services\ModelQueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class ByIsActiveFilterPipe extends AbstractFilterPipe
{
    /**
     * @param Request $request
     */
    public function __construct(protected Request $request)
    {
        $this->setFilterFieldName('is_active');

        parent::__construct($request);
    }

    /**
     * @param Builder $query
     * @param Closure $next
     * @return mixed
     */
    public function handle(Builder $query, Closure $next): mixed
    {
        $filterFieldName = $this->getFilterFieldName();
        $filterFieldValue = $this->getFilterFieldValue(); // "Да" (1) / "Нет" (2)

        if (!empty($filterFieldName) && !empty($filterFieldValue)) {
            $realValue = 0;

            if ((int) $filterFieldValue == 1) { // "Да" (1)
                $realValue = 1;
            }

            $query->where($filterFieldName, '=', $realValue);
        }

        return $next($query);
    }
}
