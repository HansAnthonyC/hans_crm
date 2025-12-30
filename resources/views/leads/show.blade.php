<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Lead
            </h2>
            <a href="{{ route('leads.index') }}" class="text-indigo-600 hover:text-indigo-800">
                ← Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Lead Info Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $lead->company_name }}</h3>
                            <p class="text-gray-500">Kontak: {{ $lead->contact_person }}</p>
                        </div>
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            @if($lead->status === 'new') bg-blue-100 text-blue-800
                            @elseif($lead->status === 'contacted') bg-yellow-100 text-yellow-800
                            @elseif($lead->status === 'qualified') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $lead->status_label }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                            <p class="text-gray-900">{{ $lead->email ?? '-' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Telepon</h4>
                            <p class="text-gray-900">{{ $lead->phone ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Alamat</h4>
                            <p class="text-gray-900">{{ $lead->address ?? '-' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Dibuat Oleh</h4>
                            <p class="text-gray-900">{{ $lead->creator->name }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Tanggal Dibuat</h4>
                            <p class="text-gray-900">{{ $lead->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 pt-6 border-t border-gray-200 flex flex-wrap gap-3">
                        <a href="{{ route('leads.edit', $lead) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                            Edit Lead
                        </a>
                        @if($lead->canCreateProject() && !Auth::user()->isManager())
                        <a href="{{ route('projects.create', ['lead_id' => $lead->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Buat Project
                        </a>
                        @endif
                        @if(!$lead->hasProject())
                        <form method="POST" action="{{ route('leads.destroy', $lead) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus lead ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Project Info (if exists) -->
            @if($lead->project)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Project Terkait</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900">{{ $lead->project->project_number }}</p>
                                <p class="text-sm text-gray-500">Dibuat oleh {{ $lead->project->creator->name }} pada {{ $lead->project->created_at->format('d M Y') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($lead->project->status === 'draft') bg-gray-100 text-gray-800
                                @elseif($lead->project->status === 'pending_approval') bg-yellow-100 text-yellow-800
                                @elseif($lead->project->status === 'approved') bg-green-100 text-green-800
                                @elseif($lead->project->status === 'rejected') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ $lead->project->status_label }}
                            </span>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('projects.show', $lead->project) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                Lihat Detail Project →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
