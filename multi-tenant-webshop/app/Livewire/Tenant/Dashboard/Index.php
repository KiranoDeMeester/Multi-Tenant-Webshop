<?php

namespace App\Livewire\Tenant\Dashboard;

use App\Models\Tenant\Product;
use App\Models\Tenant\Category;
use App\Models\Tenant\Order;
use App\Models\Tenant\OrderItem;
use App\Models\Tenant\Customer;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Index extends Component
{
    public function render()
    {
        $last30Days = Carbon::now()->subDays(30);

        // 1. Sales Volume (Last 30 Days)
        $salesData = Order::where('created_at', '>=', $last30Days)
            ->where('status', 'paid')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        // Fill missing dates with 0
        $chartSalesLabels = [];
        $chartSalesData = [];
        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartSalesLabels[] = Carbon::parse($date)->format('d M');
            $chartSalesData[] = ($salesData[$date] ?? 0) / 100; // In euros
        }

        // 2. Top Products (By Quantity Sold)
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // 3. Customer Growth (Last 30 Days)
        $customerData = Customer::where('created_at', '>=', $last30Days)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        $chartCustomerLabels = [];
        $chartCustomerData = [];
        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartCustomerLabels[] = Carbon::parse($date)->format('d M');
            $chartCustomerData[] = $customerData[$date] ?? 0;
        }

        return view('livewire.tenant.dashboard.index', [
            'productCount' => Product::count(),
            'categoryCount' => Category::count(),
            'stockWarningCount' => Product::where('stock', '<', 5)->count(),
            'totalRevenue' => Order::where('status', 'paid')->sum('total_amount') / 100,
            
            // Chart Data
            'chartSales' => [
                'labels' => $chartSalesLabels,
                'data' => $chartSalesData,
            ],
            'chartTopProducts' => [
                'labels' => $topProducts->pluck('product_name'),
                'data' => $topProducts->pluck('total_quantity'),
            ],
            'chartCustomers' => [
                'labels' => $chartCustomerLabels,
                'data' => $chartCustomerData,
            ],
        ])->layout('layouts.tenant');
    }
}
