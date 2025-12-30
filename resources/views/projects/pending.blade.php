<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Project Menunggu Approval</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div>
            @endif

            @if($projects->isEmpty())
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak Ada Project Pending</h3>
                    <p class="mt-2 text-gray-500">Semua project sudah diproses.</p>
                </div>
            </div>
            @else
            <div class="space-y-6">
                @foreach($projects as $project)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $project->project_number }}</h3>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Menunggu Approval</span>
                                </div>
                                <p class="text-gray-600">{{ $project->lead->company_name }} - {{ $project->lead->contact_person }}</p>
                                <p class="text-sm text-gray-500 mt-1">Dibuat oleh {{ $project->creator->name }} • {{ $project->created_at->format('d M Y H:i') }}</p>
                                
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($project->products as $product)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-sm">{{ $product->name }}</span>
                                    @endforeach
                                </div>
                                
                                <p class="mt-3 text-lg font-bold text-indigo-600">Total: {{ $project->formatted_total_price }}/bln</p>
                            </div>
                            
                            <div class="mt-4 lg:mt-0 lg:ml-6 flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('projects.show', $project) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-center">
                                    Detail
                                </a>
                                <form method="POST" action="{{ route('projects.approve', $project) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                        ✓ Setujui
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $projects->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
