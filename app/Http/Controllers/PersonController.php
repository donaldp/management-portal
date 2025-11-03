<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\Person;
use App\Services\PersonService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonController extends Controller
{
    /**
     * The service responsible for handling person-related operations.
     *
     * @var \App\Services\PersonService $personService
     */
    protected PersonService $personService;

    /**
     * Show all people.
     *
     * @param Request $request
     * @return
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'name', 'language_id', 'interest_id', 'archived'
        ]);

        return inertia('Dashboard', [
            'people' => $this->personService->get($filters),
            'filters' => $filters,
            'interests' => \App\Models\Interest::select('id', 'name')->get(),
            'languages' => \App\Models\Language::select('id', 'name')->get(),
        ]);
    }

    /**
     * Instantiate controller.
     *
     * @param \App\Services\PersonService $personService
     */
    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }

    /**
     * Show form for creating a new person.
     *
     * @return \Inertia\Response
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('People/Create', [
            'interests' => \App\Models\Interest::select('id', 'name')->get(),
            'languages' => \App\Models\Language::select('id', 'name')->get(),
        ]);
    }

    /**
     * Create person.
     *
     * @param \App\Http\Requests\StorePersonRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePersonRequest $request): \Illuminate\Http\RedirectResponse
    {
        $person = $this->personService->store($request->validated());

        return redirect()->route('people.update', [
            'person' => $person->id
        ]);
    }

    /**
     * Show person edit view.
     *
     * @param \App\Models\Person $person
     * @return \Inertia\Response
     */
    public function show(Person $person): \Inertia\Response
    {
        return Inertia::render('People/Edit', [
            'person' => $person->load('interests'),
            'interests' => \App\Models\Interest::select('id', 'name')->get(),
            'languages' => \App\Models\Language::select('id', 'name')->get(),
        ]);
    }

    /**
     * Update person information.
     *
     * @param \App\Http\Requests\UpdatePersonRequest $request
     * @param \App\Models\Person $person
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePersonRequest $request, Person $person): \Illuminate\Http\RedirectResponse
    {
        $this->personService->update($person, $request->validated());

        return back();
    }

    /**
     * Archive a person.
     *
     * @param \App\Models\Person $person
     * @return \Illuminate\Http\RedirectResponse
     */
    public function archive(Person $person): \Illuminate\Http\RedirectResponse
    {
        $this->personService->archive($person);

        return back();
    }

    /**
     * Unarchive a person.
     *
     * @param \App\Models\Person $person
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unarchive(Person $person): \Illuminate\Http\RedirectResponse
    {
        $this->personService->unarchive($person);

        return back();
    }
}
