<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

if (!function_exists("menu_active")) {
    function menu_active($routes, $withCurrent = false)
    {
        foreach ($routes as $route) {
            try {
                if (Request::fullUrl() != route($route) && Request::route()->getName() == $route) {
                    return true;
                } else if ($withCurrent && Request::fullUrl() == route($route)) {
                    return true;
                }
            } catch (RouteNotFoundException $e) {
                if (Request::fullUrl() != route(str_replace('*', 'index', $route)) && Route::is($route)) {
                    return true;
                }
            }
        }
        return false;
    }
}
if (!function_exists("languages_list")) {
    function languages_list()
    {
        $all_langs = LaravelLocalization::getSupportedLanguagesKeys();
        $default_lang = config('app.fallback_locale');
        $all_langs = array_filter($all_langs, function ($val) use ($default_lang) {
            return $val != $default_lang;
        });
        return $all_langs;
    }
}

if (!function_exists('ldir')) {
    function ldir()
    {
        return LaravelLocalization::getCurrentLocaleDirection();
    }
}
if (!function_exists('locales')) {
    function locales()
    {
        return LaravelLocalization::getSupportedLocales();
    }
}
if (!function_exists('lroute')) {
    function lroute($locale)
    {
        return LaravelLocalization::getLocalizedURL($locale);
    }
}
if (!function_exists('locale')) {
    function locale()
    {
        return LaravelLocalization::getCurrentLocale();
    }
}
if (!function_exists('ordinal')) {
    function ordinal($num)
    {
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::ORDINAL);
        return $numberFormatter->format($num);
    }
}
if (!function_exists("inp_value")) {
    function inp_value($obj, $name)
    {
        return old($name) ? old($name) : ($obj ? $obj->$name : '');
    }
}

if (!function_exists("inp_value_for_record")) {
    function inp_value_for_record($obj, $name, $key)
    {
        $value =  old($name) ? old($name) : ($obj ? $obj[$key] : '');
        return $value ? $value : '';
    }
}

if (!function_exists("select_value")) {
    function select_value($obj, $name, $value)
    {
        return old($name) ? (old($name) == $value ? 'selected' : '') : ($obj ? ($obj->$name == $value ? 'selected' : '') : '');
    }
}
if (!function_exists("crop")) {
    function crop($file, $details_json)
    {
        $cropping = json_decode($details_json, true);
        $x = (int)$cropping['x'];
        $y = (int)$cropping['y'];
        $w = (int)$cropping['width'];
        $h = (int)$cropping['height'];
        $img = Image::make(storage_path('app/public/' . $file));
        $crop_path = storage_path('app/public/' . $file);
        $img->crop($w, $h, $x, $y);
        $img->save($crop_path);
    }
}
if (!function_exists("storage_asset")) {
    function storage_asset($file, $secure = null)
    {
        return asset('storage/' . $file, $secure);
    }
}
