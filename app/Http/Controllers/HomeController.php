<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::active()->withCount('activeDoctors')->get();

        $featuredDoctors = Doctor::featured()
            ->with('department')
            ->select('id', 'name', 'slug', 'designation', 'department_id', 'photo', 'degrees')
            ->limit(8)
            ->get();

        $searchQuery = $request->input('q');
        $searchResults = collect();

        if ($searchQuery) {
            $searchResults = Doctor::active()
                ->with('department')
                ->where(function ($query) use ($searchQuery) {
                    $query->where('name', 'like', "%{$searchQuery}%")
                        ->orWhere('designation', 'like', "%{$searchQuery}%")
                        ->orWhere('degrees', 'like', "%{$searchQuery}%");
                })
                ->limit(10)
                ->get();
        }

        return view('home.index', compact('departments', 'featuredDoctors', 'searchQuery', 'searchResults'));
    }
}
