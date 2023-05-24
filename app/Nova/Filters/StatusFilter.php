<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class StatusFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    public $name = 'Status';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return match($value) {
            'settled' => $query->where(fn($q) => $q->whereNotNull('settled_at')->whereNull('canceled_at'))
                ->orWhereHas('parent', fn($q) => $q->whereNotNull('settled_at')->whereNull('canceled_at')),
            'not_settled' => $query->where(fn($q) => $q->whereNull('settled_at')->whereNull('canceled_at'))
                ->orWhereHas('parent', fn($q) => $q->whereNull('settled_at')->whereNull('canceled_at')),
            'canceled' => $query->where(fn($q) => $q->whereNotNull('canceled_at'))
                ->orWhereHas('parent', fn($q) => $q->whereNotNull('canceled_at')),
            default => $query,
        };
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return [
            'Betalda' => 'settled',
            'Ej betalda' => 'not_settled',
            'Avbrutna' => 'canceled',
        ];
    }

    public function default() {
        return 'settled';
    }
}
