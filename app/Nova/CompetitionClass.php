<?php

namespace App\Nova;

use App\Nova\Filters\CompetitionYearFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class CompetitionClass extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\CompetitionClass>
     */
    public static $model = \App\Models\CompetitionClass::class;

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
            Text::make('Namn', 'name'),
            BelongsTo::make('Tävlingsår', 'competitionYear', CompetitionYear::class)->default(fn() => \App\Models\CompetitionYear::current()->first()?->id),
            Currency::make('Pris', 'price'),
            Currency::make('Pris vid flera', 'price_multiple'),
            Currency::make('Tillägg vid sen anmälan', 'price_late'),
            Boolean::make('Gratis Katrineholms Kommun', 'is_free_when_local'),
            HasMany::make('Löpare', 'competitors', CompetitionYear::class),
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
        return [
            new CompetitionYearFilter(),
        ];
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

    public static function label() {
        return 'Löparklasser';
    }

    public static function singularLabel() {
        return 'Löparklass';
    }
}
