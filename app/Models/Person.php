<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'language_id',
        'first_name',
        'last_name',
        'id_number',
        'mobile_number',
        'email_address',
        'date_of_birth',
        'archived_at',
    ];

    /**
     * User's interests.
     */
    public function interests()
    {
        return $this->belongsToMany(Interest::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
