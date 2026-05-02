<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StatusOverride;
use Illuminate\Support\Facades\Auth;

class AdminStatusController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['teacher', 'faculty'])->get();
        return view('admin.status', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'status' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
        ]);

        StatusOverride::create([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'set_by_admin_id' => Auth::id(),
        ]);

        return back()->with('success', 'Status override applied.');
    }
}