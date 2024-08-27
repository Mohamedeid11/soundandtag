<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\EditSettings;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(Request $request){
        $this->authorize('viewAny', Setting::class);
        $must_exist = config('settings');
        foreach ($must_exist as $item){
            Setting::firstOrCreate($item[0], $item[1]);
        }
        $setting = new Setting;
        return view('admin.settings.index', compact('setting'));
    }
    public function update(EditSettings $request, Setting $setting){
            if($setting->input_type != 'file' && $setting->input_type != 'checkbox') {
                $setting->update(['value' => $request->input($setting->name)]);
            }
            else if ($setting->input_type == 'checkbox'){
                $setting->update(['value' => $request->filled($setting->name) ? '1' : null]);
            }
            else if ($setting->input_type == 'file'){
                if($request->hasFile($setting->name)) {
                    Storage::disk('public')->delete($setting->value);
                    $path = $request->file($setting->name)->store('uploads/settings', ['disk' => 'public']);
                    $setting->update(['value' => $path]);
                }
            }
        return [
         'status' => 1,
            'message'=> __('admin.success_edit', ['thing'=>__('global.settings')])
        ];
    }
}
