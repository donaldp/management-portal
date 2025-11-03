<?php

namespace App\Services;

use App\Jobs\AlertPersonViaEmail;
use App\Models\Person;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PersonService
{
    /**
     * Get people.
     *
     * @param array filters
     */
    public function get(array $filters)
    {
        $query = Person::with(['language', 'interests']);

        $archiveFilter = $filters['archived'] ?? 'active';

        if ($archiveFilter === 'active') {
            $query->whereNull('archived_at');
        } elseif ($archiveFilter === 'archived') {
            $query->whereNotNull('archived_at');
        }

        if (!empty($filters['name'])) {
            $names = explode(' ', $filters['name']);
            $query->where(function ($q) use ($names) {
                if (count($names) === 1) {
                    $q->where('first_name', 'like', "%{$names[0]}%")
                        ->orWhere('last_name', 'like', "%{$names[0]}%");
                } else {
                    $q->where('first_name', 'like', "%{$names[0]}%")
                        ->where('last_name', 'like', "%{$names[1]}%");
                }
            });
        }

        if (!empty($filters['language_id'])) {
            $query->where('language_id', $filters['language_id']);
        }

        if (!empty($filters['interest_id'])) {
            $query->whereHas('interests', function ($q) use ($filters) {
                $q->where('interests.id', $filters['interest_id']);
            });
        }

        return $query->paginate(20)->appends($filters);
    }

    /**
     * Store person record into the db.
     *
     * @param array $data
     * @return \App\Models\Person
     */
    public function store(array $data): Person
    {
        return DB::transaction(function () use ($data) {
            /** store person. */
            $person = Person::create(
                Arr::except($data, 'interests')
            );

            /** sync interests. */
            if (!empty($data['interests']) && is_array($data['interests'])) {
                $person->interests()->sync(array_map(fn($interest) => $interest['id'], $data['interests']));
            }

            AlertPersonViaEmail::dispatch($person);

            return $person;
        });
    }

    /**
     * Update person record in the db.
     *
     * @param \App\Models\Person $person
     * @param array $data
     * @return \App\Models\Person
     */
    public function update(Person $person, array $data): Person
    {
        return DB::transaction(function () use ($person, $data) {
            /** update person. */
            $person->update(
                Arr::except($data, 'interests')
            );

            /** sync interests. */
            if (!empty($data['interests']) && is_array($data['interests'])) {
                $person->interests()->sync(array_map(fn($interest) => $interest['id'], $data['interests']));
            }

            return $person;
        });
    }

    /**
     * Archive a person.
     *
     * @param \App\Models\Person $person
     * @return \App\Models\Person
     */
    public function archive(Person $person): Person
    {
        $person->update([
            'archived_at' => now()
        ]);

        return $person;
    }

    /**
     * Unarchive a person.
     *
     * @param \App\Models\Person $person
     * @return \App\Models\Person
     */
    public function unarchive(Person $person): Person
    {
        $person->update([
            'archived_at' => null
        ]);

        return $person;
    }
}
