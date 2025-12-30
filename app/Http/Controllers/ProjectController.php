<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Product;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['lead', 'creator', 'approver', 'products']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_number', 'ilike', "%{$search}%")
                  ->orWhereHas('lead', function ($leadQuery) use ($search) {
                      $leadQuery->where('company_name', 'ilike', "%{$search}%");
                  });
            });
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function pending()
    {
        $projects = Project::with(['lead', 'creator', 'products'])
            ->where('status', 'pending_approval')
            ->latest()
            ->paginate(10);

        return view('projects.pending', compact('projects'));
    }

    public function create(Request $request)
    {
        if (Auth::user()->isManager()) {
            return redirect()->route('projects.index')
                ->with('error', 'Manager tidak dapat membuat project. Silakan gunakan akun Sales.');
        }

        $leads = Lead::where('status', '!=', 'unqualified')
            ->whereDoesntHave('project', function ($query) {
                $query->whereNotIn('status', ['rejected']);
            })
            ->get();

        $products = Product::where('is_active', true)->get();

        $selectedLeadId = $request->get('lead_id');

        return view('projects.create', compact('leads', 'products', 'selectedLeadId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $lead = Lead::findOrFail($validated['lead_id']);
        if ($lead->hasProject()) {
            return redirect()->back()
                ->with('error', 'Lead ini sudah memiliki project!')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $project = Project::create([
                'lead_id' => $validated['lead_id'],
                'created_by' => Auth::id(),
                'status' => 'draft',
                'notes' => $validated['notes'],
            ]);

            foreach ($validated['products'] as $productData) {
                $product = Product::find($productData['id']);
                $project->products()->attach($product->id, [
                    'quantity' => $productData['quantity'],
                    'price' => $product->price,
                ]);
            }

            DB::commit();

            return redirect()->route('projects.show', $project)
                ->with('success', 'Project berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Project $project)
    {
        $project->load(['lead', 'creator', 'approver', 'products', 'customer']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        if ($project->status !== 'draft') {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Hanya project dengan status draft yang dapat diedit!');
        }

        $leads = Lead::whereDoesntHave('project')
            ->orWhere('id', $project->lead_id)
            ->where('status', '!=', 'unqualified')
            ->get();

        $products = Product::where('is_active', true)->get();

        return view('projects.edit', compact('project', 'leads', 'products'));
    }

    public function update(Request $request, Project $project)
    {
        if ($project->status !== 'draft') {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Hanya project dengan status draft yang dapat diedit!');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $project->update([
                'notes' => $validated['notes'],
            ]);

            $syncData = [];
            foreach ($validated['products'] as $productData) {
                $product = Product::find($productData['id']);
                $syncData[$product->id] = [
                    'quantity' => $productData['quantity'],
                    'price' => $product->price,
                ];
            }
            $project->products()->sync($syncData);

            DB::commit();

            return redirect()->route('projects.show', $project)
                ->with('success', 'Project berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function submit(Project $project)
    {
        if (!$project->canBeSubmitted()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Project tidak dapat diajukan untuk persetujuan!');
        }

        $project->submitForApproval();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project berhasil diajukan untuk persetujuan manager!');
    }

    public function approve(Project $project)
    {
        $user = Auth::user();

        if (!$user->isManager() && !$user->isAdmin()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Anda tidak memiliki izin untuk menyetujui project!');
        }

        if (!$project->canBeApproved()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Project tidak dapat disetujui!');
        }

        try {
            DB::beginTransaction();

            $project->approve($user);

            Customer::createFromProject($project);

            DB::commit();

            return redirect()->route('projects.show', $project)
                ->with('success', 'Project berhasil disetujui dan customer telah dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('projects.show', $project)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Project $project)
    {
        $user = Auth::user();

        if (!$user->isManager() && !$user->isAdmin()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Anda tidak memiliki izin untuk menolak project!');
        }

        if (!$project->canBeApproved()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Project tidak dapat ditolak!');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $project->reject($user, $validated['rejection_reason']);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project telah ditolak.');
    }

    public function destroy(Project $project)
    {
        if ($project->status !== 'draft') {
            return redirect()->route('projects.index')
                ->with('error', 'Hanya project dengan status draft yang dapat dihapus!');
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project berhasil dihapus!');
    }
}
