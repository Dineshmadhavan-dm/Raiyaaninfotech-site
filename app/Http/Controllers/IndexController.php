<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function home()
    {
        $site = Site::first();
        if (!$site) {
            $site = Site::create([
                'status' => 1, // Default to site ON
                'page_name' => 'index'
            ]);
        }


        return  view($site->status == 1 ? 'index' : 'site');
    }
}
