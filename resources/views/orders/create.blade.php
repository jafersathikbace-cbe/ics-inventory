<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Place Order</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <p class="text-sm text-gray-500 mb-4">
                        Order placement will check stock and apply rules:
                        <b>allow</b>, <b>allow with warning</b>, or <b>block</b>.
                    </p>

                    @if(session('error'))
                        <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('orders.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product</label>
                            <select name="product_id" class="mt-1 w-full rounded border-gray-300" required>
                                <option value="">Select product</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Make sure BOM is configured for the selected product.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input name="quantity" type="number" step="0.001" min="0.001"
                                   value="{{ old('quantity', 1) }}"
                                   class="mt-1 w-full rounded border-gray-300" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes (optional)</label>
                            <input name="notes" value="{{ old('notes') }}" class="mt-1 w-full rounded border-gray-300" maxlength="500">
                        </div>

                        <div class="flex items-center gap-3">
                            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Place Order
                            </button>
                            <a href="{{ route('orders.index') }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
