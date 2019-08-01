<?php

namespace App\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return (new static)->getTable();
    }
}
