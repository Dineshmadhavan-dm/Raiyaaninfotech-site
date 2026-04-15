<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
        public function __construct()
{
    // Restrict all Kanban board routes to only Super Admin (categorie 1) and Admin (categorie 3)
    $this->middleware(function ($request, $next) {
        $user = auth()->user();

        // Check user category - only allow 1 (Super Admin) and 3 (Admin)
        if ($user->categorie == 2) { // Employee
            return redirect()->route('emphome')->with('error', 'Access denied .');
        }

        return $next($request);
    });


}

    public function index()
    {
        $clients = Client::where('delete_status', 1)->get();
        return view('dashboard.hr.client.index', compact('clients'));
    }

    public function create()
    {
        return view('dashboard.hr.client.create');
    }

    public function store(Request $request)
    {
        // Remove the 'string' validation for cl_image
        $request->validate([
            'cl_name' => 'required|string|max:255',
            'cl_email' => 'required|email|unique:clients,cl_email',
            'cl_password' => 'required|min:8',
            'cl_image' => 'nullable',
        ]);

        $client = new Client();
        $client->cl_name = $request->cl_name;
        $client->cl_email = $request->cl_email;
        $client->cl_password = $request->cl_password;

        // Process image only if it's a base64 string
        if ($request->filled('cl_image') && is_string($request->cl_image)) {
            $this->processBase64Image($request->cl_image, $client);
        }

        $client->save();

        return response()->json([
            'status' => true,
            'message' => 'Client created successfully'
        ]);
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json([
            'status' => true,
            'client' => $client
        ]);
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('dashboard.hr.client.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cl_name' => 'required|string|max:255',
            'cl_email' => 'required|email|unique:clients,cl_email,' . $id . ',cl_id',
            'cl_password' => 'nullable|min:8',
            'confirm_password' => 'nullable|same:cl_password',
            'cl_image' => 'nullable',
        ]);

        $client = Client::findOrFail($id);
        $client->cl_name = $request->cl_name;
        $client->cl_email = $request->cl_email;

        if ($request->filled('cl_password')) {
            $client->cl_password = $request->cl_password;
        }

        if ($request->filled('cl_image') && is_string($request->cl_image)) {
            $this->processBase64Image($request->cl_image, $client);
        }

        $client->save();

        return response()->json([
            'status' => true,
            'message' => 'Client updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->update(['delete_status' => 0]);

        return response()->json([
            'status' => true,
            'message' => 'Client deleted successfully'
        ]);
    }

    private function processBase64Image($imageData, $client)
    {
        // Check if it's a base64 image string
        if (strpos($imageData, 'data:image') === 0) {
            $parts = explode(',', $imageData);

            // Get mime type and extension
            $mime = explode(';', explode(':', $parts[0])[1])[0];

            $extension = '';
            switch($mime) {
                case 'image/jpeg':
                    $extension = 'jpg';
                    break;
                case 'image/png':
                    $extension = 'png';
                    break;
                case 'image/gif':
                    $extension = 'gif';
                    break;
                default:
                    return;
            }

            $image = base64_decode($parts[1]);
            $imageName = 'client_' . time() . '_' . uniqid() . '.' . $extension;
            $folderPath = public_path('client_images');

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $imagePath = $folderPath . '/' . $imageName;
            file_put_contents($imagePath, $image);

            if ($client->cl_image && file_exists($folderPath . '/' . $client->cl_image)) {
                unlink($folderPath . '/' . $client->cl_image);
            }

            $client->cl_image = $imageName;
        }
    }
}
