<?php

namespace App\Livewire\Tenant\Dashboard;

use App\Models\Tenant\Category;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Order;
use App\Models\Tenant\OrderItem;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductVariation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public string $dateRange = '30';

    public string $status = 'paid';

    protected $queryString = [
        'dateRange' => ['except' => '30'],
        'status' => ['except' => 'paid'],
    ];

    public function render()
    {
        $lowStockProducts = Product::doesntHave('variations')
            ->where('stock', '<', 5)
            ->count();

        $lowStockVariations = ProductVariation::where('stock', '<', 5)
            ->count();

        // Calculate days to look back
        $days = match ($this->dateRange) {
            '7' => 7,
            '30' => 30,
            '90' => 90,
            'all' => (int) Carbon::now()->diffInDays(Order::min('created_at') ?? Carbon::now()->subYear()),
            default => 30,
        };
        // Clamp to a maximum of 365 to keep the charts readable and fast
        $days = min($days, 365);
        $dateFilter = Carbon::now()->subDays($days);

        // 1. Sales Volume (Filtered by Date Range and Status)
        $salesData = Order::query()
            ->when($this->dateRange !== 'all', function ($query) use ($dateFilter) {
                $query->where('created_at', '>=', $dateFilter);
            })
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
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
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartSalesLabels[] = Carbon::parse($date)->format('d M');
            $chartSalesData[] = ($salesData[$date] ?? 0) / 100; // In euros
        }

        // 2. Top Products (By Quantity Sold, Filtered by Date Range and Status)
        $topProducts = OrderItem::query()
            ->whereHas('order', function ($query) use ($dateFilter) {
                $query->when($this->dateRange !== 'all', function ($q) use ($dateFilter) {
                    $q->where('created_at', '>=', $dateFilter);
                })
                    ->when($this->status !== 'all', function ($q) {
                        $q->where('status', $this->status);
                    });
            })
            ->select('product_name', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // 3. Customer Growth (Last X Days)
        $customerData = Customer::query()
            ->when($this->dateRange !== 'all', function ($query) use ($dateFilter) {
                $query->where('created_at', '>=', $dateFilter);
            })
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
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartCustomerLabels[] = Carbon::parse($date)->format('d M');
            $chartCustomerData[] = $customerData[$date] ?? 0;
        }

        // Filtered Total Revenue Card
        $totalRevenue = Order::query()
            ->when($this->dateRange !== 'all', function ($query) use ($dateFilter) {
                $query->where('created_at', '>=', $dateFilter);
            })
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->sum('total_amount') / 100;

        return view('livewire.tenant.dashboard.index', [
            'productCount' => Product::count(),
            'categoryCount' => Category::count(),
            'stockWarningCount' => $lowStockProducts + $lowStockVariations,
            'totalRevenue' => $totalRevenue,

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
