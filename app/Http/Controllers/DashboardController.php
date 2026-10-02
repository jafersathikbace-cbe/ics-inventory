<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $todayStart = now()->startOfDay();
        $todayEnd   = now()->endOfDay();

        // Orders today
        $ordersTodayQuery = Order::whereBetween('placed_at', [$todayStart, $todayEnd]);

        $ordersTodayTotal = (clone $ordersTodayQuery)->count();

        $statusCounts = [
            'Received'    => (clone $ordersTodayQuery)->where('status', 'Received')->count(),
            'In Progress' => (clone $ordersTodayQuery)->where('status', 'In Progress')->count(),
            'Completed'   => (clone $ordersTodayQuery)->where('status', 'Completed')->count(),
            'Dispatched'  => (clone $ordersTodayQuery)->where('status', 'Dispatched')->count(),
        ];

        // Latest orders (all-time)
        $latestOrders = Order::with(['user', 'items.product'])
            ->orderByDesc('placed_at')
            ->limit(10)
            ->get();

        // Low stock materials
        $lowMaterialsQuery = Material::with('unit')
            ->whereColumn('stock', '<=', 'threshold')
            ->orderByRaw('(threshold - stock) DESC');

        $lowStockCount = (clone $lowMaterialsQuery)->count();
        $lowMaterials  = (clone $lowMaterialsQuery)->limit(10)->get();

        // Top low materials for chart (stock vs threshold)
        $topLowForChart = Material::with('unit')
            ->whereColumn('stock', '<=', 'threshold')
            ->orderByRaw('(threshold - stock) DESC')
            ->limit(8)
            ->get()
            ->map(function ($m) {
                return [
                    'name' => $m->name,
                    'unit' => $m->unit?->symbol ?? '',
                    'stock' => (float)$m->stock,
                    'threshold' => (float)$m->threshold,
                ];
            })
            ->values()
            ->all();

        // Donut chart data (today)
        $donutLabels = array_keys($statusCounts);
        $donutValues = array_values($statusCounts);

        return view('dashboard.index', [
            'ordersTodayTotal' => $ordersTodayTotal,
            'statusCounts' => $statusCounts,
            'lowStockCount' => $lowStockCount,
            'latestOrders' => $latestOrders,
            'lowMaterials' => $lowMaterials,
            'donutLabels' => $donutLabels,
            'donutValues' => $donutValues,
            'topLowForChart' => $topLowForChart,
        ]);
    }
}
