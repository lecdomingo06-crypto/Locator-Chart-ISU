<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class AdminStatusController extends Controller
{
    public function index()
    {
        $users = User::with('department')
            ->whereIn('role', ['professor', 'faculty'])
            ->where('is_suspended', false)
            ->orderBy('full_name')
            ->get();

        return view('admin.status', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->whereIn('role', ['professor', 'faculty'])
                    ->where('is_suspended', false)),
            ],
            'status' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
        ]);

        $request->user()->statusOverridesSet()->create([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
        ]);

        return back()->with('success', 'Status override applied.');
    }
}
