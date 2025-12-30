<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Customer</h2>
            <a href="{{ route('customers.index') }}" class="text-indigo-600 hover:text-indigo-800">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer Info -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-sm text-gray-500">{{ $customer->customer_number }}</p>
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $customer->company_name }}</h3>
                                </div>
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-{{ $customer->status_color }}-100 text-{{ $customer->status_color }}-800">
                                    {{ $customer->status_label }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Kontak Person</p>
                                    <p class="font-medium text-gray-900">{{ $customer->contact_person }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-medium text-gray-900">{{ $customer->email ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Telepon</p>
                                    <p class="font-medium text-gray-900">{{ $customer->phone ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Mulai Berlangganan</p>
                                    <p class="font-medium text-gray-900">{{ $customer->subscription_start?->format('d M Y') ?? '-' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-500">Alamat</p>
                                    <p class="font-medium text-gray-900">{{ $customer->address ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subscribed Services -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Layanan Berlangganan</h4>
                            
                            <div class="space-y-3">
                                @forelse($customer->products as $product)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg {{ $product->pivot->status === 'inactive' ? 'opacity-50' : '' }}">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 text-xs font-medium rounded bg-{{ $product->type === 'fiber' ? 'blue' : ($product->type === 'wireless' ? 'purple' : 'orange') }}-100 text-{{ $product->type === 'fiber' ? 'blue' : ($product->type === 'wireless' ? 'purple' : 'orange') }}-800">
                                                {{ $product->type_label }}
                                            </span>
                                            <span class="px-2 py-0.5 text-xs font-medium rounded {{ $product->pivot->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $product->pivot->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </div>
                                        <p class="font-semibold text-gray-900 mt-1">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $product->code }} • {{ $product->speed }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            Mulai: {{ \Carbon\Carbon::parse($product->pivot->start_date)->format('d M Y') }}
                                            @if($product->pivot->end_date)
                                            • Berakhir: {{ \Carbon\Carbon::parse($product->pivot->end_date)->format('d M Y') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-indigo-600">Rp {{ number_format($product->pivot->monthly_price, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500">/ bulan</p>
                                    </div>
                                </div>
                                @empty
                                <p class="text-gray-500 text-center py-4">Tidak ada layanan</p>
                                @endforelse
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                <span class="text-lg font-medium text-gray-700">Total Bulanan:</span>
                                <span class="text-2xl font-bold text-indigo-600">{{ $customer->formatted_total_monthly }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                    <!-- Actions -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h4>
                            <div class="space-y-3">
                                <a href="{{ route('customers.edit', $customer) }}" class="block w-full px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 text-center">
                                    Edit Customer
                                </a>
                                
                                <form method="POST" action="{{ route('customers.update-status', $customer) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach(\App\Models\Customer::STATUS_LABELS as $value => $label)
                                        <option value="{{ $value }}" {{ $customer->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Project Origin -->
                    @if($customer->project)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Asal Project</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="font-medium text-gray-900">{{ $customer->project->project_number }}</p>
                                <p class="text-sm text-gray-500">Dibuat oleh {{ $customer->project->creator->name }}</p>
                                <p class="text-xs text-gray-400">{{ $customer->project->created_at->format('d M Y H:i') }}</p>
                                <a href="{{ route('projects.show', $customer->project) }}" class="inline-block mt-2 text-sm text-indigo-600 hover:text-indigo-800">Lihat Project →</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Quick Info -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Info</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm text-gray-500">Layanan Aktif</dt>
                                    <dd class="font-medium text-gray-900">{{ $customer->activeProducts->count() }} layanan</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Terdaftar</dt>
                                    <dd class="font-medium text-gray-900">{{ $customer->created_at->format('d M Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
