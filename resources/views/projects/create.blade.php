<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Project Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">{{ session('error') }}</div>
            @endif

            @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('projects.store') }}" id="projectForm">
                        @csrf

                        <!-- Lead Selection -->
                        <div class="mb-6">
                            <label for="lead_id" class="block text-sm font-medium text-gray-700">Pilih Lead <span class="text-red-500">*</span></label>
                            <select name="lead_id" id="lead_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('lead_id') border-red-500 @enderror">
                                <option value="">-- Pilih Lead --</option>
                                @foreach($leads as $lead)
                                <option value="{{ $lead->id }}" {{ old('lead_id', $selectedLeadId) == $lead->id ? 'selected' : '' }}>
                                    {{ $lead->company_name }} - {{ $lead->contact_person }}
                                </option>
                                @endforeach
                            </select>
                            @error('lead_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            @if($leads->isEmpty())
                            <p class="mt-2 text-sm text-yellow-600">Tidak ada lead yang tersedia. <a href="{{ route('leads.create') }}" class="text-indigo-600 underline">Buat lead baru</a></p>
                            @endif
                        </div>

                        <!-- Products Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Produk <span class="text-red-500">*</span></label>
                            <p class="text-sm text-gray-500 mb-3">Centang produk yang ingin ditambahkan ke project</p>
                            
                            <div class="space-y-3" id="product-list">
                                @foreach($products as $index => $product)
                                <div class="product-item flex items-center p-4 border border-gray-200 rounded-lg hover:border-indigo-300 transition-colors" data-product-id="{{ $product->id }}" data-product-price="{{ $product->price }}">
                                    <input type="checkbox" 
                                        id="product_check_{{ $product->id }}"
                                        class="product-checkbox h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                        data-product-id="{{ $product->id }}"
                                        onchange="updateTotal()">
                                    <label for="product_check_{{ $product->id }}" class="ml-4 flex-1 cursor-pointer">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $product->code }} • {{ $product->speed ?? 'N/A' }} • {{ $product->type_label }}</p>
                                            </div>
                                            <p class="font-bold text-indigo-600">{{ $product->formatted_price }}/bln</p>
                                        </div>
                                    </label>
                                    <div class="ml-4 w-24">
                                        <input type="number" 
                                            id="product_qty_{{ $product->id }}"
                                            value="1" min="1"
                                            class="product-quantity block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center"
                                            data-product-id="{{ $product->id }}"
                                            onchange="updateTotal()">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Hidden inputs for selected products (will be populated by JS) -->
                            <div id="selected-products-container"></div>
                            
                            @error('products')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Total -->
                        <div class="mb-6 p-4 bg-indigo-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-medium text-gray-700">Total Estimasi / Bulan:</span>
                                <span class="text-2xl font-bold text-indigo-600" id="total-price">Rp 0</span>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Simpan Project</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateTotal() {
            let total = 0;
            const container = document.getElementById('selected-products-container');
            container.innerHTML = ''; // Clear previous hidden inputs
            
            let productIndex = 0;
            
            document.querySelectorAll('.product-item').forEach((item) => {
                const productId = item.dataset.productId;
                const price = parseFloat(item.dataset.productPrice) || 0;
                const checkbox = item.querySelector('.product-checkbox');
                const quantityInput = item.querySelector('.product-quantity');
                const quantity = parseInt(quantityInput.value) || 1;
                
                if (checkbox.checked) {
                    total += price * quantity;
                    
                    // Create hidden inputs for this product
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = `products[${productIndex}][id]`;
                    idInput.value = productId;
                    container.appendChild(idInput);
                    
                    const qtyInput = document.createElement('input');
                    qtyInput.type = 'hidden';
                    qtyInput.name = `products[${productIndex}][quantity]`;
                    qtyInput.value = quantity;
                    container.appendChild(qtyInput);
                    
                    productIndex++;
                }
            });
            
            document.getElementById('total-price').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
        
        // Run on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateTotal();
        });
    </script>
</x-app-layout>
