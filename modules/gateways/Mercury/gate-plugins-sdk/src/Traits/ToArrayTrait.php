<?php

namespace MercuryCash\SDK\Traits;

trait ToArrayTrait
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter(get_object_vars($this), function($value) {
            return !is_null($value) && $value !== '';
        });
    }
}
