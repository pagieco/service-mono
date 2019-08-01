<?php

namespace App\Concerns;

use Webpatser\Uuid\Uuid;

trait HasUUID
{
    /**
     * Boot the HasUUID trait.
     *
     * This will create a new unique identifier when the model is creating.
     * Nothing is done when a UUID has already been set.
     *
     * @return void
     */
    protected static function bootHasUUID(): void
    {
        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Uuid::generate(4);
            }
        });
    }

    /**
     * Disable the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}
