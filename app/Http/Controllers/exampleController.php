<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class exampleController extends Controller
{
    public function homepage()
    {   
        $names=["vahid","majid","behnam"];
        return view("homepage",["names"=>$names]);
    }
    public function aboutusPage()
    {
        return view("aboutuspage");
    }
    //
}
