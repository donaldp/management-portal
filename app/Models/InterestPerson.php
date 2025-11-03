<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestPerson extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'person_id',
        'interest_id',
    ];
}
