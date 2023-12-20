<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageUserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('manageuser.index');
    }

    public function create()
    {
        return view('manageuser.create');
    }
}
