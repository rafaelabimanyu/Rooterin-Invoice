<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $language = \App\Models\Setting::get('language', 'id');
                App::setLocale($language);

                $timezone = \App\Models\Setting::get('timezone', 'Asia/Jakarta');
                date_default_timezone_set($timezone);
                config(['app.timezone' => $timezone]);
            } else {
                if (Session::has('locale')) {
                    App::setLocale(Session::get('locale'));
                } elseif (auth()->check()) {
                    App::setLocale(auth()->user()->locale);
                } else {
                    App::setLocale(config('app.locale'));
                }
            }
        } catch (\Exception $e) {
            if (Session::has('locale')) {
                App::setLocale(Session::get('locale'));
            } elseif (auth()->check()) {
                App::setLocale(auth()->user()->locale);
            } else {
                App::setLocale(config('app.locale'));
            }
        }

        return $next($request);
    }
}
