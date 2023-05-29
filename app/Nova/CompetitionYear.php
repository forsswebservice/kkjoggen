<?php

namespace App\Nova;

use App\Nova\Filters\CompetitionYearFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class CompetitionYear extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\CompetitionYear>
     */
    public static $model = \App\Models\CompetitionYear::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Namn', 'name')->sortable(),
            Boolean::make('Aktuell anmälningsår?', 'is_current'),
            DateTime::make('Anmälan öppnar', 'opens_at')->displayUsing(fn($v) => $v?->format('Y-m-d H:i')),
            DateTime::make('Anmälan stänger', 'closes_at')->displayUsing(fn($v) => $v?->format('Y-m-d H:i')),
            DateTime::make('Sen anmälan från', 'late_registration_at')->displayUsing(fn($v) => $v?->format('Y-m-d H:i')),
            Number::make('Max antal löpare vid anmälan', 'max_registration'),
            Number::make('Grupprabatt från antal', 'rebate_from'),
            Number::make('Grupprabatt %', 'rebate_percent'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static function singularLabel() {
        return 'Tävlingsår';
    }

    public static function label() {
        return 'Tävlingsår';
    }
}
