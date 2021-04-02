<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;

class Model extends BaseModel
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if($model->columnExists('uuid')){
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    private function columnExists(string $columnName)
    {
        $schema = $this->getConnection()->getSchemaBuilder();
        $table = $this->getTable();
        return ($schema->hasColumn($table, $columnName)) ?  true : false;
    }
}
