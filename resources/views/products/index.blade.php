<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Produk
            </h2>
            @if(Auth::user()->isAdmin() || Auth::user()->isManager())
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Produk
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
            @endif

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Cari nama atau kode produk..." 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="w-40">
                            <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Tipe</option>
                                @foreach(\App\Models\Product::TYPE_LABELS as $value => $label)
                                <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-40">
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Filter</button>
                        <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Reset</a>
                    </form>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg {{ !$product->is_active ? 'opacity-60' : '' }}">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($product->type === 'fiber') bg-blue-100 text-blue-800
                                    @elseif($product->type === 'wireless') bg-purple-100 text-purple-800
                                    @else bg-orange-100 text-orange-800
                                    @endif">
                                    {{ $product->type_label }}
                                </span>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500 mb-2">{{ $product->code }}</p>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>
                        
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs text-gray-500">Kecepatan</p>
                                <p class="font-medium text-gray-900">{{ $product->speed ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Harga/Bulan</p>
                                <p class="font-bold text-indigo-600">{{ $product->formatted_price }}</p>
                            </div>
                        </div>

                        @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                        <div class="flex space-x-2 pt-4 border-t border-gray-200">
                            <a href="{{ route('products.edit', $product) }}" class="flex-1 px-3 py-2 text-center text-sm bg-yellow-500 text-white rounded-md hover:bg-yellow-600">Edit</a>
                            <form method="POST" action="{{ route('products.toggle-status', $product) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 text-sm {{ $product->is_active ? 'bg-gray-500 hover:bg-gray-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-md">
                                    {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12 text-gray-500">
                    Tidak ada produk ditemukan
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
