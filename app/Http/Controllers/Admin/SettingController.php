<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'app_favicon' => 'nullable|image|mimes:png,jpg,jpeg,ico|max:1024',
        ]);

        Setting::where('key', 'app_name')->update(['value' => $request->app_name]);

        if ($request->hasFile('app_logo')) {
            // Hapus file lama agar tidak jadi sampah
            $oldLogo = Setting::where('key', 'app_logo')->first()->value ?? null;
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $filename = 'logo_' . time() . '.' . $request->file('app_logo')->getClientOriginalExtension();
            $path = $request->file('app_logo')->storeAs('settings', $filename, 'public');
            Setting::where('key', 'app_logo')->update(['value' => $path]);
        }

        if ($request->hasFile('app_favicon')) {
            $oldFavicon = Setting::where('key', 'app_favicon')->first()->value ?? null;
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }

            $filename = 'favicon_' . time() . '.' . $request->file('app_favicon')->getClientOriginalExtension();
            $path = $request->file('app_favicon')->storeAs('settings', $filename, 'public');
            Setting::where('key', 'app_favicon')->update(['value' => $path]);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
