<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Customer</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('customers.update', $customer) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="company_name" class="block text-sm font-medium text-gray-700">Nama Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $customer->company_name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('company_name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="contact_person" class="block text-sm font-medium text-gray-700">Kontak Person <span class="text-red-500">*</span></label>
                            <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $customer->contact_person) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('contact_person')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="address" id="address" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $customer->address) }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach(\App\Models\Customer::STATUS_LABELS as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $customer->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('customers.show', $customer) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
