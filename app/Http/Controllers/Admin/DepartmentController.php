<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDepartmentRequest;
use App\Http\Requests\Admin\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $this->authorize('department.view');
        $departments = Department::withCount('doctors')->orderBy('order')->paginate(15);

        return view('admin.departments.index', compact('departments'));
    }

    public function create(): View
    {
        $this->authorize('department.create');

        return view('admin.departments.create');
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        Department::create($request->validated());

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department): View
    {
        $this->authorize('department.edit');

        return view('admin.departments.edit', compact('department'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->authorize('department.delete');

        if ($department->doctors()->exists()) {
            return back()->with('error', 'Cannot delete department with assigned doctors.');
        }

        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted.');
    }
}
