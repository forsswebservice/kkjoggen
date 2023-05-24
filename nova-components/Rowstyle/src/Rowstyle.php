<?php

namespace Kkjoggen\Rowstyle;

use Laravel\Nova\Fields\Field;

class Rowstyle extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'rowstyle';

    public function jsonSerialize(): array
    {
        return array_merge([
            'style' => $this->style(),
        ], parent::jsonSerialize());
    }

    private function style() : ?string
    {
        $status = call_user_func($this->computedCallback);

        return match($status) {
            'Betald' => 'background: #d9f99d;',
            'Ej betald' => 'background: #fecaca;',
            'Avbruten' => 'background: #fed7aa;',
            default => '',
        };
    }

    public function onlyOnIndex() {
        return true;
    }
}
