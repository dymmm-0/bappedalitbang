<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Beranda_Controller extends Controller
{
    public function index(){
        $title = 'Beranda Admin';

        $settings = all_settings();

        return view('admin.beranda.beranda_index', compact('title', 'settings'));
    }
}