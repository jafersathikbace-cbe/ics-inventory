<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Orders</h2>
                <p class="text-sm text-gray-500">Place orders, update status, filter by date/status.</p>
            </div>

            @can('orders.create')
                <a href="{{ route('orders.create') }}"
                   class="px-4 py-2 rounded bg-indigo-600 text-white text-sm hover:bg-indigo-700">
                    + Place Order
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white shadow-sm sm:rounded-lg border mb-4">
                <div class="p-4">
                    <form method="GET" action="{{ route('orders.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 w-full rounded border-gray-300 text-sm">
                                <option value="">All</option>
                                @foreach($allowedStatuses as $st)
                                    <option value="{{ $st }}" {{ ($filterStatus === $st) ? 'selected' : '' }}>
                                        {{ $st }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <input name="date" class="mt-1 w-full rounded border-gray-300 text-sm"
                                   placeholder="today or YYYY-MM-DD"
                                   value="{{ $filterDate ?? '' }}">
                            <p class="text-xs text-gray-500 mt-1">Use: <b>today</b> or <b>2026-01-31</b></p>
                        </div>

                        <div class="flex gap-2">
                            <button class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-gray-800 text-sm">
                                Apply
                            </button>

                            <a href="{{ route('orders.index') }}"
                               class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm">
                                Clear
                            </a>
                        </div>

                        <div class="text-sm text-gray-500">
                            Showing: <b>{{ $orders->total() }}</b> orders
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border">
                <div class="p-6">

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-600 border-b">
                                    <th class="py-2">Order No</th>
                                    <th class="py-2">Placed At</th>
                                    <th class="py-2">Placed By</th>
                                    <th class="py-2">Product</th>
                                    <th class="py-2">Qty</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $o)
                                    @php $item = $o->items->first(); @endphp
                                    <tr class="border-b">
                                        <td class="py-3 font-semibold">{{ $o->order_no }}</td>
                                        <td class="py-3">{{ $o->placed_at?->format('Y-m-d H:i') }}</td>
                                        <td class="py-3">{{ $o->user?->name }}</td>
                                        <td class="py-3">{{ $item?->product?->name ?? '-' }}</td>
                                        <td class="py-3">{{ $item?->quantity ?? '-' }}</td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 rounded text-xs
                                                {{ $o->status === 'Received' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $o->status === 'In Progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $o->status === 'Completed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $o->status === 'Dispatched' ? 'bg-purple-100 text-purple-800' : '' }}
                                            ">
                                                {{ $o->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-right">
                                            @can('orders.update_status')
                                                <form method="POST" action="{{ route('orders.status.update', $o) }}" class="inline-flex items-center gap-2">
                                                    @csrf
                                                    <select name="status" class="rounded border-gray-300 text-sm">
                                                        <option value="Received" {{ $o->status === 'Received' ? 'selected' : '' }}>Received</option>
                                                        <option value="In Progress" {{ $o->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                        <option value="Completed" {{ $o->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                                        <option value="Dispatched" {{ $o->status === 'Dispatched' ? 'selected' : '' }}>Dispatched</option>
                                                    </select>
                                                    <button class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">
                                                        Update
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-sm text-gray-500">No permission</span>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-gray-500">
                                            No orders found for the selected filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
