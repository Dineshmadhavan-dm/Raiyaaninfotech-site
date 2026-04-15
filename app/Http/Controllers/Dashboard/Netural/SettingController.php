<?php

namespace App\Http\Controllers\Dashboard\Netural;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{




    public function __construct()
    {

        $this->middleware('permission:site->applogo view')->only(['applogo']);
        $this->middleware('permission:site->applogo create')->only(['applogo_post']);
        $this->middleware('permission:site->favicon view')->only(['favicon']);
        $this->middleware('permission:site->favicon create')->only(['favicon_post']);
        // $this->middleware('permission:site->cookie view')->only(['cookies']);
        $this->middleware('permission:site->sitecontrol view')->only(['sitecontrol']);
        $this->middleware('permission:site->sitecontrol create')->only(['toggleStatus']);
        // $this->middleware('permission:site->theme view')->only(['theme']);
        // $this->middleware('permission:site->theme create')->only(['theme_post']);
    }


    public function applogo()
    {
        // Get the first settings record or create a new empty instance
        $settingsapplogo = Setting::firstOrNew();

        return view('dashboard.netural.setting.applogo', compact('settingsapplogo'));
    }

    public function applogo_post(Request $request)
    {
        $isUpdate = Setting::exists();

        $request->validate([
            'weblogo' => $isUpdate ? 'nullable|string|starts_with:data:image' : 'required|string|starts_with:data:image',
            'webname' => 'required|string|max:100',
        ]);

        try {
            $webName = $request->input('webname');
            $imageData = $request->input('weblogo');

            // Get existing settings or create new
            $settings = Setting::firstOrNew();

            // Handle image only if provided
            if ($imageData) {
                // Extract image type and data
                if (!preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid image format. Only JPG, JPEG, and PNG are allowed.'
                    ], 422);
                }

                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to process image.'
                    ], 422);
                }

                // Delete old logo if exists
                if ($settings->weblogo && file_exists(public_path('weblogo/' . $settings->weblogo))) {
                    unlink(public_path('weblogo/' . $settings->weblogo));
                }

                $imageName = 'weblogo' . '.' . 'png'; // Add timestamp for uniqueness
                $folderPath = public_path('weblogo');

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }

                file_put_contents($folderPath . '/' . $imageName, $imageData);
                $settings->weblogo = $imageName;
            }

            $settings->webname = $webName;
            $settings->save();

            return response()->json([
                'success' => true,
                'message' => 'Successfully ' . ($settings->wasRecentlyCreated ? 'added' : 'updated') . ' website settings'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }




    public function  favicon()
    {
        $settingsfavicon = Setting::first();
        return view('dashboard.netural.setting.favicon', compact('settingsfavicon'));
    }


    public function favlogo_post(Request $request)
    {
        $isUpdate = Setting::exists();

        $request->validate([
            'favlogo' => $isUpdate ? 'nullable|string|starts_with:data:image' : 'required|string|starts_with:data:image',

        ]);

        try {

            $imageData = $request->input('favlogo');

            // Get existing settings or create new
            $settings = Setting::firstOrNew();

            // Handle image only if provided
            if ($imageData) {
                // Extract image type and data
                if (!preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid image format. Only ICO and PNG are allowed.'
                    ], 422);
                }

                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to process image.'
                    ], 422);
                }

                // Delete old logo if exists
                if ($settings->favlogo && file_exists(public_path('favlogo/' . $settings->favlogo))) {
                    unlink(public_path('favlogo/' . $settings->favlogo));
                }

                $imageName = 'favlogo' . '.ico'; // Add timestamp for uniqueness
                $folderPath = public_path('favlogo');

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }

                file_put_contents($folderPath . '/' . $imageName, $imageData);
                $settings->favlogo = $imageName;
            }


            $settings->save();

            return response()->json([
                'success' => true,
                'message' => 'Successfully ' . ($settings->wasRecentlyCreated ? 'added' : 'updated') . ' favicon'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }








    public function  cookies()
    {

        return view('dashboard.netural.setting.cookies');
    }






    public function clearCookies(Request $request)
    {
        try {
            $response = response()->json([
                'success' => true,
                'message' => 'Operation completed successfully',
                'reload' => false
            ]);

            // Handle cookies
            if ($request->action === 'cookies' || $request->action === 'all') {
                $preserveCookies = $this->getPreservedCookies($request);
                $cookies = $request->cookies->all();

                foreach ($cookies as $name => $value) {
                    if (!in_array($name, $preserveCookies)) {
                        $response->withCookie(Cookie::forget($name));
                    }
                }
            }

            // Handle sessions
            if ($request->action === 'sessions' || $request->action === 'all') {
                Session::flush();
                $response->setData([
                    'success' => true,
                    'message' => 'All sessions cleared successfully',
                    'reload' => true
                ]);
            }

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function getPreservedCookies(Request $request)
    {
        $preserve = [];

        if ($request->preserveAuth) {
            $preserve[] = config('session.cookie');
            $preserve[] = 'XSRF-TOKEN';
        }

        if ($request->preservePreferences) {
            $preserve[] = 'theme_preference';
            $preserve[] = 'language';
        }

        return $preserve;
    }















    public function  sitecontrol()
    {
        $site = Site::first();
        return view('dashboard.netural.setting.sitecontrol', compact('site'));
    }

    public function toggleStatus(Request $request)
    {
        $site = Site::first();

        if (!$site) {

            $site = Site::create([
                'status' => 1,
                'page_name' => 'index'
            ]);
        } else {

            $newStatus = $site->status == 1 ? 0 : 1;
            $newPageName = $newStatus == 1 ? 'index' : 'site';

            $site->update([
                'status' => $newStatus,
                'page_name' => $newPageName
            ]);
        }

        return response()->json([
            'message' => $site->status == 1 ? " Site ON" : " Site Off - Under Construction 🚧"
        ]);
    }











    public function theme()
    {
        $settings = auth()->user()->settings()->firstOrNew([
            'p_color' => '#4477b6',
            'nh_color' => '#ffffff',
            'h_color' => '#ffffff',
            's_color' => '#ffffff',
            'selected_palette' => null,
            'enable_palettes' => false,
            'enable_custom_colors' => true
        ]);

        return view('dashboard.netural.setting.theme', compact('settings'));
    }

    public function theme_post(Request $request)
    {
        $validated = $request->validate([
            'p_color' => ['required', 'string', 'regex:/^#[a-f0-9]{6}$/i'],
            'nh_color' => ['required', 'string', 'regex:/^#[a-f0-9]{6}$/i'],
            'h_color' => ['required', 'string', 'regex:/^#[a-f0-9]{6}$/i'],
            's_color' => ['required', 'string', 'regex:/^#[a-f0-9]{6}$/i'],
            'selected_palette' => ['nullable', 'string', 'in:ocean,forest,sunset'],
            'enable_palettes' => ['required', 'boolean'],
            'enable_custom_colors' => ['required', 'boolean'],
        ]);

        // Set proper mode
        if ($validated['enable_palettes'] && $validated['selected_palette']) {
            $validated['enable_custom_colors'] = false;
        } else {
            $validated['selected_palette'] = null;
            $validated['enable_palettes'] = false;
            $validated['enable_custom_colors'] = true;
        }

        auth()->user()->settings()->updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return back()->with('successalert', 'Theme settings updated successfully!');
    }

    public function theme_reset()
    {
        auth()->user()->settings()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'p_color' => '#4477b6',
                'nh_color' => '#ffffff',
                'h_color' => '#ffffff',
                's_color' => '#ffffff',
                'selected_palette' => null,
                'enable_palettes' => false,
                'enable_custom_colors' => true
            ]
        );

        return back()->with('successalert', 'Theme colors reset to defaults!');
    }
}
