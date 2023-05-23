<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Competitor extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Competitor>
     */
    public static $model = \App\Models\Competitor::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'firstname';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'firstname',
        'lastname',
        'email',
        'phone',
    ];

    public static $indexDefaultOrder = [
        'id' => 'asc'
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            return $query->orderBy(key(static::$indexDefaultOrder), reset(static::$indexDefaultOrder));
        }
        return $query;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make("Löpnummer", 'id')->sortable(),
            Text::make('Förnamn', 'firstname')->nullable()->sortable(),
            Text::make('Efternamn', 'lastname')->nullable()->sortable(),
            Number::make('Födelseår', 'born')->nullable(),
            Text::make('Gatuadress', 'address')->nullable()->hideFromIndex(),
            Text::make('Postnummer', 'zip_code')->nullable()->hideFromIndex(),
            Text::make('Ort', 'city')->nullable()->hideFromIndex(),
            Text::make('Telefonnummer', 'phone')->nullable(),
            Email::make('E-postadress', 'email')->nullable(),
            Text::make('Referensnummer', 'reference_number')->nullable()->hideFromIndex(),
            Text::make('Företag/Lag', 'team')->nullable()->hideFromIndex(),
            BelongsTo::make('Löparklass', 'competitionClass', CompetitionClass::class)->nullable(),
            BelongsTo::make('Tävlingsår', 'competitionYear', CompetitionYear::class)->nullable(),
            Text::make('Tröjstorlek', 'shirt_size')->nullable()->hideFromIndex(),
            Text::make('Namntryck', 'shirt_name')->nullable()->hideFromIndex(),
            Number::make('Tidigare starter', 'previous_starts')->nullable()->hideFromIndex(),
            Number::make('Tid 10km', 'time_10k')->nullable()->hideFromIndex(),
            DateTime::make('Anmäld', 'created_at')->hideWhenCreating()->hideWhenUpdating()->readonly(),
            DateTime::make('Betald', 'settled_at')->nullable(),
            Boolean::make('Bosatt i Katrineholms Kommun', 'is_local'),
            Number::make('Pris', 'price')->readonly(),
            HasMany::make('Löpare', 'children', Competitor::class),
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

    public static function label() {
        return 'Löpare';
    }

    public static function singularLabel() {
        return 'Löpare';
    }
}
