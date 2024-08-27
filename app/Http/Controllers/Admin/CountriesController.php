<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Countries\CreateCountry;
use App\Http\Requests\Admin\Countries\DeleteCountry;
use App\Http\Requests\Admin\Countries\EditCountry;
use App\Models\Country;
use App\Services\Admin\CountriesCrudService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;

class CountriesController extends BaseAdminController
{
    private $countriesCrudServices;
//    private $model;

    /**
     * CountriesController constructor.
     * Authorize requests using App\Policies\Admin\CountryPolicy.
     * @param CountriesCrudService $countriesCrudService
     */
    public function __construct(CountriesCrudService $countriesCrudService)
    {
        parent::__construct(Country::class);
        $this->authorizeResource(Country::class);
        $this->countriesCrudServices = $countriesCrudService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $countries = $this->countriesCrudServices->getAllCountries();
        return view('admin.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $country = new Country;
        return view('admin.countries.create-edit', compact('country'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateCountry $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(CreateCountry $request)
    {
        $this->countriesCrudServices->createCountry($request->all(), $request->allFiles());
        return redirect(route('admin.countries.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Country $country
     * @return Application|Factory|View|Response
     */
    public function edit(Country $country)
    {
        return view('admin.countries.create-edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditCountry $request
     * @param Country $country
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(EditCountry $request, Country $country)
    {
        $this->countriesCrudServices->updateCountry($country, $request->all(), $request->allFiles());
        return redirect(route('admin.countries.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteCountry $request
     * @param Country $country
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function destroy(DeleteCountry $request, Country $country)
    {
        $this->countriesCrudServices->deleteCountry($country);
        return redirect(route('admin.countries.index'));
    }

    /**
     * @param DeleteCountry $request
     * @return Application|RedirectResponse|Redirector
     */
    public function batchDestroy(DeleteCountry $request){
        $this->countriesCrudServices->batchDeleteCountries($request->all());
        return redirect(route('admin.countries.index'));
    }

    /**
     *
     * @param $country_id
     * @return false|string
     * @throws AuthorizationException
     */
    public function toggle_active($country_id){
        $this->authorize('viewAny', Country::class);
        $this->countriesCrudServices->toggleCountryActive($country_id);
        return json_encode([
            'status' => 1,
            'message' => __("admin.status_success")
        ]);
    }
}
