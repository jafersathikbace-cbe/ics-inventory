<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
                <p class="text-sm text-gray-500">
                    Today: {{ now()->format('Y-m-d') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="bg-white border rounded-lg p-5">
                    <div class="text-sm text-gray-500">Orders Today</div>
                    <div class="text-2xl font-bold">{{ $ordersTodayTotal }}</div>
                </div>

                <div class="bg-white border rounded-lg p-5">
                    <div class="text-sm text-gray-500">Received</div>
                    <div class="text-2xl font-bold">{{ $statusCounts['Received'] }}</div>
                </div>

                <div class="bg-white border rounded-lg p-5">
                    <div class="text-sm text-gray-500">In Progress</div>
                    <div class="text-2xl font-bold">{{ $statusCounts['In Progress'] }}</div>
                </div>

                <div class="bg-white border rounded-lg p-5">
                    <div class="text-sm text-gray-500">Completed</div>
                    <div class="text-2xl font-bold">{{ $statusCounts['Completed'] }}</div>
                </div>

                <div class="bg-white border rounded-lg p-5">
                    <div class="text-sm text-gray-500">Dispatched</div>
                    <div class="text-2xl font-bold">{{ $statusCounts['Dispatched'] }}</div>
                </div>

                <div class="bg-white border rounded-lg p-5">
                    <div class="text-sm text-gray-500">Low Stock Materials</div>
                    <div class="text-2xl font-bold">{{ $lowStockCount }}</div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border rounded-lg p-6">
                    <h3 class="font-semibold mb-3">Order Status Distribution (Today)</h3>
                    <div class="h-72">
                        <canvas id="statusDonut"></canvas>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Click a slice to view orders for that status.
                    </p>
                </div>

                <div class="bg-white border rounded-lg p-6">
                    <h3 class="font-semibold mb-3">Top Low Materials: Stock vs Threshold</h3>
                    <div class="h-72">
                        <canvas id="lowBar"></canvas>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Horizontal layout improves readability for long names.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        /* ---------- Donut Chart ---------- */
        const donutLabels = @json($donutLabels);
        const donutValues = @json($donutValues);
        const ordersIndexBase = "{{ route('orders.index') }}";

        new Chart(document.getElementById('statusDonut'), {
            type: 'doughnut',
            data: {
                labels: donutLabels,
                datasets: [{ data: donutValues }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                onClick: (evt, elements) => {
                    if (!elements.length) return;
                    const status = donutLabels[elements[0].index];
                    window.location.href = ordersIndexBase + '?date=today&status=' + encodeURIComponent(status);
                }
            }
        });

        /* ---------- Horizontal Bar Chart (BEST FIX) ---------- */
        const lowRows = @json($topLowForChart);

        new Chart(document.getElementById('lowBar'), {
            type: 'bar',
            data: {
                labels: lowRows.map(r => `${r.name} (${r.unit})`),
                datasets: [
                    { label: 'Stock', data: lowRows.map(r => r.stock) },
                    { label: 'Threshold', data: lowRows.map(r => r.threshold) }
                ]
            },
            options: {
                indexAxis: 'y',              // ✅ KEY FIX
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { beginAtZero: true },
                    y: { ticks: { autoSkip: false } }
                },
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</x-app-layout>
