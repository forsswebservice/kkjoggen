<?php

namespace App\Nova;

use App\Nova\Filters\CompetitionYearFilter;
use App\Nova\Filters\StatusFilter;
use Illuminate\Http\Request;
use Kkjoggen\Rowstyle\Rowstyle;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
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
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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

    public function title()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    protected static function applySearch($query, $search) {
        if($competition_year = \App\Models\CompetitionYear::current()->first()) {
            return $query
                ->where('competition_year_id', $competition_year->id)
                ->whereNotNull('settled_at')
                ->whereNull('canceled_at')
                ->where(fn($q) =>
                    $q->where('id', '=', $search)
                        ->orWhere('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%")
                        ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE '%{$search}%'")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhereHas('parent', fn($q) =>
                            $q->where('id', '=', $search)
                                ->orWhere('firstname', 'LIKE', "%{$search}%")
                                ->orWhere('lastname', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%")
                                ->orWhere('phone', 'LIKE', "%{$search}%")
                        )->orWhereHas('children', fn($q) =>
                            $q->where('id', '=', $search)
                                ->orWhere('firstname', 'LIKE', "%{$search}%")
                                ->orWhere('lastname', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%")
                                ->orWhere('phone', 'LIKE', "%{$search}%")
                        )
                    );
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
        $fields = [
            Rowstyle::make('Status', fn() => $this->getStatus()),
            BelongsTo::make('Betalare', 'parent', Competitor::class)->hideFromIndex()->display(fn($c) => "#{$c->id} {$c->firstname} {$c->lastname}")->searchable(),
            ID::make("Referensnummer", 'id')->sortable(),
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
            BelongsTo::make('Tävlingsår', 'competitionYear', CompetitionYear::class)->nullable()->hideFromIndex()->default(fn() => \App\Models\CompetitionYear::current()->first()?->id),
            Text::make('Tröjstorlek', 'shirt_size')->nullable()->hideFromIndex(),
            Text::make('Namntryck', 'shirt_name')->nullable()->hideFromIndex(),
            //Number::make('Tidigare starter', 'previous_starts')->nullable()->hideFromIndex(),
            Number::make('Tid 10km', 'time_10k')->nullable()->hideFromIndex(),
            DateTime::make('Anmäld', 'created_at')->hideWhenCreating()->hideWhenUpdating()->readonly()->displayUsing(fn($value) => $value->format('Y-m-d H:i')),
            Boolean::make('Bosatt i Katrineholms Kommun', 'is_local')->hideFromIndex(),
            Currency::make('Pris', 'price')->readonly(),
            Currency::make('Rabatt', 'rebate')->readonly(),
            DateTime::make('Betalt', 'settled_at')->displayUsing(fn($v) => $v?->format("Y-m-d H:i"))->hideWhenUpdating(fn($c) => (bool)$c->parent)->readonly(fn($c) => (bool)$c->payment_data),
        ];

        if(!$this->parent) {
            $fields[] = HasMany::make('Löpare', 'children', Competitor::class);
        }

        return $fields;
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
            new StatusFilter(),
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
        return 'Löpare';
    }

    public static function singularLabel() {
        return 'Löpare';
    }
}
