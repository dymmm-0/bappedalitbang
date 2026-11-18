<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function master(){
		return view('master');
	}
 
	// public function visi(){
	// 	return view('visi');
	// }
	// public function teshome(){
	// 	return view('teshome');
	// }

}
