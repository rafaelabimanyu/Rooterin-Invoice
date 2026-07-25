<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        abort_if(!in_array(auth()->user()->role, ['owner', 'admin']), 403, 'Unauthorized access.');
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['owner', 'admin']), 403, 'Unauthorized access.');
        $data = $request->except('_token');
        
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
