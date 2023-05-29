<?php

namespace App\Traits;

trait StoresSumsInCents
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($value && property_exists($this, 'sums') && is_array($this->sums) && in_array($key, $this->sums)) {
            return rescue(fn () => $value / 100, 0.0, false);
        }

        return $value;
    }

    public function setAttribute($key, $value)
    {
        if (property_exists($this, 'sums') && is_array($this->sums) && in_array($key, $this->sums) && ! (! $value && property_exists($this, 'nullable_sums') && is_array($this->nullable_sums) && in_array($key, $this->nullable_sums))) {
            $value = rescue(fn () => round($value * 100), 0.0, false);
        }

        return parent::setAttribute($key, $value);
    }

    public function toArray()
    {
        $attributes = parent::toArray();

        if (! property_exists($this, 'sums') || ! is_array($this->sums)) {
            return $attributes;
        }

        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->sums)) {
                $attributes[$key] = rescue(fn () => $value / 100, 0.0, false);
            }
        }

        return $attributes;
    }
}
