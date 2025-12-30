<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with('creator');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'ilike', "%{$search}%")
                  ->orWhere('contact_person', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('phone', 'ilike', "%{$search}%");
            });
        }

        $leads = $query->latest()->paginate(10)->withQueryString();

        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        return view('leads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,unqualified',
        ]);

        $validated['created_by'] = Auth::id();

        Lead::create($validated);

        return redirect()->route('leads.index')
            ->with('success', 'Lead berhasil ditambahkan!');
    }

    public function show(Lead $lead)
    {
        $lead->load(['creator', 'project.products', 'project.creator']);
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        return view('leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,unqualified',
        ]);

        $lead->update($validated);

        return redirect()->route('leads.index')
            ->with('success', 'Lead berhasil diperbarui!');
    }

    public function destroy(Lead $lead)
    {
        if ($lead->hasProject()) {
            return redirect()->route('leads.index')
                ->with('error', 'Tidak dapat menghapus lead yang sudah memiliki project!');
        }

        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead berhasil dihapus!');
    }
}
