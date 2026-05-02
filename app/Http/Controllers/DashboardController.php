<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('dashboards.admin');
    }

    public function student()
    {
        return view('dashboards.student');
    }

    public function teacher()
    {
        return view('dashboards.teacher');
    }

    public function faculty()
    {
        return view('dashboards.faculty');
    }
}