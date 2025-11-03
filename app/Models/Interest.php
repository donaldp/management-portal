<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name',
        'description',
    ];
}
