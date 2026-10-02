<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Units</h2>

            @can('units.create')
                <a href="{{ route('units.create') }}"
                   class="px-4 py-2 rounded bg-indigo-600 text-white text-sm hover:bg-indigo-700">
                    + Add Unit
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-600 border-b">
                                    <th class="py-2">Name</th>
                                    <th class="py-2">Symbol</th>
                                    <th class="py-2">Decimal?</th>
                                    <th class="py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($units as $unit)
                                    <tr class="border-b">
                                        <td class="py-3">{{ $unit->name }}</td>
                                        <td class="py-3">{{ $unit->symbol }}</td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 rounded text-xs {{ $unit->allow_decimal ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $unit->allow_decimal ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-right space-x-2">
                                            @can('units.update')
                                                <a href="{{ route('units.edit', $unit) }}"
                                                   class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('units.delete')
                                                <form action="{{ route('units.destroy', $unit) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Delete this unit?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-gray-500">
                                            No units yet. Create units like feet, sqft, nos, ml, kg.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
