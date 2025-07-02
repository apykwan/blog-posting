<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homepage() 
    {
        $ourName = 'john doe';
        $animals = ['Shark', 'Eagle', 'Tiger'];

        return view('homepage', ['name' => $ourName, 'animals' => $animals]);
    }

    public function aboutpage()
    {
        return view('single-post');
    }
}
