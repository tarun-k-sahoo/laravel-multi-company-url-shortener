<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->paginate(10);

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
        ]);

        Company::create([
            'name' => $validated['name'],
            'status' => true,
        ]);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function toggleStatus(Company $company)
    {
        $company->update([
            'status' => !$company->status,
        ]);

        return back()->with(
            'success',
            'Company status updated successfully.'
        );
    }
}
