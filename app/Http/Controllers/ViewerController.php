<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class ViewerController extends Controller
{
    public function studentViewer(Request $request)
    {
        $search = $request->search;
        $department = $request->department;

        $users = User::with('department')
            ->whereIn('role', ['professor', 'faculty'])
            ->where('is_suspended', false)
            ->when($search, function ($query) use ($search) {
                $query->where('full_name', 'like', '%' . $search . '%');
            })
            ->when($department, function ($query) use ($department) {
                $query->where('department_id', $department);
            })
            ->get();

        $departments = Department::all();

        return view('viewers.student', compact('users', 'departments', 'search', 'department'));
    }

    public function staffViewer(Request $request)
    {
        $search = $request->search;
        $department = $request->department;

        $users = User::with('department')
            ->whereIn('role', ['professor', 'faculty'])
            ->where('is_suspended', false)
            ->when($search, function ($query) use ($search) {
                $query->where('full_name', 'like', '%' . $search . '%');
            })
            ->when($department, function ($query) use ($department) {
                $query->where('department_id', $department);
            })
            ->get();

        $departments = Department::all();

        return view('viewers.staff', compact('users', 'departments', 'search', 'department'));
    }
}