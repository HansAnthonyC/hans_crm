<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Product;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'qualified_leads' => Lead::where('status', 'qualified')->count(),
            'total_products' => Product::where('is_active', true)->count(),
            'pending_projects' => Project::where('status', 'pending_approval')->count(),
            'approved_projects' => Project::where('status', 'approved')->count(),
            'total_customers' => Customer::count(),
            'active_customers' => Customer::where('status', 'active')->count(),
        ];

        $recentLeads = Lead::with('creator')->latest()->take(5)->get();
        $recentProjects = Project::with(['lead', 'creator'])->latest()->take(5)->get();
        $pendingApprovals = Project::with(['lead', 'creator'])
            ->where('status', 'pending_approval')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentLeads', 'recentProjects', 'pendingApprovals'));
    }
}
