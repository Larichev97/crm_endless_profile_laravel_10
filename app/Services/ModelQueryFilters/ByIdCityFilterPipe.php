<?php

namespace App\Services\ModelQueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class ByIdCityFilterPipe extends AbstractFilterPipe
{
    /**
     * @param Request $request
     */
    public function __construct(protected Request $request)
    {
        $this->setFilterFieldName('id_city');

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
            $query->where($filterFieldName, '=', $filterFieldValue);
        }

        return $next($query);
    }
}
