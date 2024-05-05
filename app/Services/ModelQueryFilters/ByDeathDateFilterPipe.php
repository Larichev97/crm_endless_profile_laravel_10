<?php

namespace App\Services\ModelQueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class ByDeathDateFilterPipe extends AbstractFilterPipe
{
    /**
     * @param Request $request
     */
    public function __construct(protected Request $request)
    {
        $this->setFilterFieldName('death_date');

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
        $filterFieldValue = $this->getFilterFieldValue();

        if (!empty($filterFieldName) && !empty($filterFieldValue)) {
            $query->whereDate(column: (string) $filterFieldName, operator: '=', value: (string) $filterFieldValue);
        }

        return $next($query);
    }
}
