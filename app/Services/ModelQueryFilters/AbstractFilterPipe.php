<?php

namespace App\Services\ModelQueryFilters;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilterPipe
{
    /**
     * @var string
     */
    protected string $filterFieldName;

    /**
     * @var string
     */
    protected string $filterFieldValue;

    /**
     * @param Request $request
     */
    public function __construct(protected Request $request)
    {
        $this->setFilterFieldValue(filterFieldValue: (string) $this->request->get(key: 'filter_'.$this->getFilterFieldName(), default: ''));
    }

    /**
     * @param Builder $query
     * @param Closure $next
     * @return mixed
     */
    abstract public function handle(Builder $query, Closure $next): mixed;

    /**
     * @return string
     */
    public function getFilterFieldName(): string
    {
        return $this->filterFieldName;
    }

    /**
     * @param string $filterFieldName
     * @return void
     */
    public function setFilterFieldName(string $filterFieldName): void
    {
        $this->filterFieldName = $filterFieldName;
    }

    /**
     * @return string
     */
    public function getFilterFieldValue(): string
    {
        return $this->filterFieldValue;
    }

    /**
     * @param string $filterFieldValue
     * @return void
     */
    public function setFilterFieldValue(string $filterFieldValue): void
    {
        $this->filterFieldValue = $filterFieldValue;
    }
}
