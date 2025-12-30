<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg mb-6 p-6">
                <h3 class="text-2xl font-bold text-white">Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p class="text-indigo-100 mt-1">PT. Smart CRM - Sistem Manajemen Customer</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Leads -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Leads</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_leads'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-green-600 font-medium">{{ $stats['new_leads'] }} baru</span>
                            <span class="text-sm text-gray-400 mx-1">•</span>
                            <span class="text-sm text-blue-600 font-medium">{{ $stats['qualified_leads'] }} qualified</span>
                        </div>
                    </div>
                </div>

                <!-- Total Products -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Produk Aktif</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_products'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Projects -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Menunggu Approval</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_projects'] }}</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Total Customers -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Customers</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_customers'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-green-600 font-medium">{{ $stats['active_customers'] }} aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('leads.create') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">Tambah Lead Baru</h4>
                            <p class="text-sm text-gray-500">Input calon customer baru</p>
                        </div>
                    </div>
                </a>

                @if(Auth::user()->isSales() || Auth::user()->isAdmin())
                <a href="{{ route('projects.create') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border-l-4 border-green-500">
                    <div class="flex items-center">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">Buat Project Baru</h4>
                            <p class="text-sm text-gray-500">Proses lead menjadi customer</p>
                        </div>
                    </div>
                </a>
                @else
                <a href="{{ route('projects.pending') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border-l-4 border-green-500">
                    <div class="flex items-center">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">Review Approval</h4>
                            <p class="text-sm text-gray-500">Tinjau project yang menunggu</p>
                        </div>
                    </div>
                </a>
                @endif

                <a href="{{ route('customers.index') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">Lihat Customers</h4>
                            <p class="text-sm text-gray-500">Daftar customer berlangganan</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Leads -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Lead Terbaru</h3>
                            <a href="{{ route('leads.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Semua →</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($recentLeads as $lead)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $lead->company_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $lead->contact_person }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($lead->status === 'new') bg-blue-100 text-blue-800
                                    @elseif($lead->status === 'contacted') bg-yellow-100 text-yellow-800
                                    @elseif($lead->status === 'qualified') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $lead->status_label }}
                                </span>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">Belum ada lead</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals (for Manager/Admin) -->
                @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Menunggu Approval</h3>
                            <a href="{{ route('projects.pending') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Semua →</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($pendingApprovals as $project)
                            <a href="{{ route('projects.show', $project) }}" class="block p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $project->project_number }}</p>
                                        <p class="text-sm text-gray-500">{{ $project->lead->company_name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">{{ $project->formatted_total_price }}</p>
                                        <p class="text-xs text-gray-500">oleh {{ $project->creator->name }}</p>
                                    </div>
                                </div>
                            </a>
                            @empty
                            <p class="text-gray-500 text-center py-4">Tidak ada project yang menunggu approval</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                @else
                <!-- Recent Projects (for Sales) -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Project Terbaru</h3>
                            <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Semua →</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($recentProjects as $project)
                            <a href="{{ route('projects.show', $project) }}" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $project->project_number }}</p>
                                        <p class="text-sm text-gray-500">{{ $project->lead->company_name }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($project->status === 'draft') bg-gray-100 text-gray-800
                                        @elseif($project->status === 'pending_approval') bg-yellow-100 text-yellow-800
                                        @elseif($project->status === 'approved') bg-green-100 text-green-800
                                        @elseif($project->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ $project->status_label }}
                                    </span>
                                </div>
                            </a>
                            @empty
                            <p class="text-gray-500 text-center py-4">Belum ada project</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
