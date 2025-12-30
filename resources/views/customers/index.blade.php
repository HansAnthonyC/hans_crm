<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Customer</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div>
            @endif

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('customers.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari customer..." 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="w-40">
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Status</option>
                                @foreach(\App\Models\Customer::STATUS_LABELS as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Filter</button>
                        <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Reset</a>
                    </form>
                </div>
            </div>

            <!-- Customer Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($customers as $customer)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $customer->status_color }}-100 text-{{ $customer->status_color }}-800">
                                {{ $customer->status_label }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $customer->customer_number }}</span>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $customer->company_name }}</h3>
                        <p class="text-gray-600 text-sm mb-3">{{ $customer->contact_person }}</p>
                        
                        <div class="border-t border-gray-100 pt-3 mb-3">
                            <p class="text-xs text-gray-500 mb-1">Layanan Aktif</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($customer->activeProducts->take(3) as $product)
                                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded text-xs">{{ $product->name }}</span>
                                @endforeach
                                @if($customer->activeProducts->count() > 3)
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">+{{ $customer->activeProducts->count() - 3 }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500">Total / Bulan</p>
                                <p class="font-bold text-indigo-600">{{ $customer->formatted_total_monthly }}</p>
                            </div>
                            <a href="{{ route('customers.show', $customer) }}" class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 bg-white rounded-lg p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Customer</h3>
                    <p class="mt-2 text-gray-500">Customer akan muncul setelah project di-approve.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $customers->links() }}</div>
        </div>
    </div>
</x-app-layout>
