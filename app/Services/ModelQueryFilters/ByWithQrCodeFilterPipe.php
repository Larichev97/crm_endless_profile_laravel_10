<?php

namespace App\Services\ModelQueryFilters;

use App\Models\QrProfile;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class ByWithQrCodeFilterPipe extends AbstractFilterPipe
{
    /**
     * @param Request $request
     */
    public function __construct(protected Request $request)
    {
        $this->setFilterFieldName('with_qr_code');

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

        if (!empty($filterFieldName) && !empty($filterFieldValue) && $query->getModel() instanceof QrProfile) {
            if ((int) $filterFieldValue == 1) { // "Да" (1)
                $query->whereNotNull('qr_code_file_name');
            } else { // "Нет" (2)
                $query->orWhereNull('qr_code_file_name');
                $query->orWhere('qr_code_file_name', '=', '');
            }
        }

        return $next($query);
    }
}
