<?php
namespace App\Services\Admin;

use App\Models\Country;
use App\Repositories\Eloquent\CountryRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class CountriesCrudService {
    private $countryRepository;
    public function __construct(CountryRepository $countryRepository){
        $this->countryRepository = $countryRepository;
    }
    public function getAllCountries()
    {
       return $this->countryRepository->paginate(100);
    }

    public function createCountry(array $data, $files)
    {
        $data =  Arr::only($data, ['name', 'nationality', 'key', 'code']);
        $data['active'] = Arr::has($data, 'active');
        $data['image'] = $files['image']->store('uploads/countries', ['disk'=>'public']);
        $this->countryRepository->create($data);
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.country')]) );
    }

    public function updateCountry(Country $country, array $data, array $files)
    {
        $data =  Arr::only($data, ['name', 'nationality', 'key', 'code']);
        $data['active'] = Arr::has($data, 'active');
        if (Arr::has($files,'image')){
            Storage::disk('public')->delete($country->image);
            $data['image'] = $files['image']->store('uploads/countries', ['disk'=>'public']);
        }
        $country->update($data);
        session()->flash('success', __('admin.success_edit', ['thing'=>__('global.country')])  );
    }

    public function deleteCountry(Country $country)
    {
        Storage::disk('public')->delete($country->image);
        $country->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.country')]) );
    }

    public function batchDeleteCountries(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $target_countries = $this->countryRepository->getMany($ids);
        if (count($target_countries) > 0) {
            Storage::disk('public')->delete(...$target_countries->pluck('image'));
        }
        $this->countryRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.country')]) );
    }

    public function toggleCountryActive($country_id)
    {
        $country = $this->countryRepository->get($country_id);
        $country ? $country->update(['active'=>!$country->active]) : null;
    }
}
