<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Project</h2>
            <a href="{{ route('projects.index') }}" class="text-indigo-600 hover:text-indigo-800">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Project Info -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $project->project_number }}</h3>
                                    <p class="text-gray-500">Dibuat {{ $project->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-{{ $project->status_color }}-100 text-{{ $project->status_color }}-800">
                                    {{ $project->status_label }}
                                </span>
                            </div>

                            <!-- Lead Info -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Informasi Lead</h4>
                                <p class="font-semibold text-gray-900">{{ $project->lead->company_name }}</p>
                                <p class="text-gray-600">{{ $project->lead->contact_person }}</p>
                                <p class="text-sm text-gray-500">{{ $project->lead->email }} • {{ $project->lead->phone }}</p>
                            </div>

                            <!-- Products -->
                            <h4 class="text-sm font-medium text-gray-500 mb-3">Produk Dipilih</h4>
                            <div class="space-y-2">
                                @foreach($project->products as $product)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $product->code }} • {{ $product->speed }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500">x{{ $product->pivot->quantity }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                <span class="text-lg font-medium text-gray-700">Total / Bulan:</span>
                                <span class="text-2xl font-bold text-indigo-600">{{ $project->formatted_total_price }}</span>
                            </div>

                            @if($project->notes)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Catatan</h4>
                                <p class="text-gray-700">{{ $project->notes }}</p>
                            </div>
                            @endif

                            @if($project->status === 'rejected' && $project->rejection_reason)
                            <div class="mt-4 p-4 bg-red-50 rounded-lg">
                                <h4 class="text-sm font-medium text-red-800 mb-2">Alasan Penolakan</h4>
                                <p class="text-red-700">{{ $project->rejection_reason }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions Sidebar -->
                <div class="space-y-6" x-data="{ showRejectForm: false }">
                    <!-- Actions Card -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h4>
                            
                            <div class="space-y-3">
                                @if($project->canBeSubmitted())
                                <form method="POST" action="{{ route('projects.submit', $project) }}">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        Ajukan Approval
                                    </button>
                                </form>
                                <a href="{{ route('projects.edit', $project) }}" class="block w-full px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 text-center">
                                    Edit Project
                                </a>
                                @endif

                                @if($project->canBeApproved() && (Auth::user()->isManager() || Auth::user()->isAdmin()))
                                <form method="POST" action="{{ route('projects.approve', $project) }}" onsubmit="return confirm('Setujui project ini?')">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                        ✓ Setujui Project
                                    </button>
                                </form>
                                
                                <button @click="showRejectForm = !showRejectForm" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    ✕ Tolak Project
                                </button>

                                <div x-show="showRejectForm" x-cloak class="mt-3">
                                    <form method="POST" action="{{ route('projects.reject', $project) }}">
                                        @csrf
                                        <textarea name="rejection_reason" rows="3" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Alasan penolakan..."></textarea>
                                        <button type="submit" class="mt-2 w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                            Konfirmasi Tolak
                                        </button>
                                    </form>
                                </div>
                                @endif

                                @if($project->status === 'completed' && $project->customer)
                                <a href="{{ route('customers.show', $project->customer) }}" class="block w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-center">
                                    Lihat Customer
                                </a>
                                @endif

                                @if($project->status === 'draft')
                                <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Hapus project ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                        Hapus Project
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm text-gray-500">Dibuat Oleh</dt>
                                    <dd class="font-medium text-gray-900">{{ $project->creator->name }}</dd>
                                </div>
                                @if($project->approver)
                                <div>
                                    <dt class="text-sm text-gray-500">{{ $project->status === 'rejected' ? 'Ditolak' : 'Disetujui' }} Oleh</dt>
                                    <dd class="font-medium text-gray-900">{{ $project->approver->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Waktu</dt>
                                    <dd class="font-medium text-gray-900">{{ $project->approved_at->format('d M Y H:i') }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
