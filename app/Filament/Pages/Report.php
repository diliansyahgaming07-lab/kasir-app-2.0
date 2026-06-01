<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanPenjualanExport;
use Maatwebsite\Excel\Facades\Excel;

class Report extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.report';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?int $navigationSort = 2;
    
    public $startDate = null;
    public $endDate = null;
    public $totalPenjualan = 0;
    public $totalTransaksi = 0;
    public $totalProdukTerjual = 0;
    public $rataRataTransaksi = 0;
    public $transactions = [];
    public $chartData = [];
    
    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->loadData();
        $this->loadChartData();
    }
    
    public function loadData()
    {
        $query = Transaction::where('status', 'completed');
        
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        
        $this->totalPenjualan = $query->sum('total_amount');
        $this->totalTransaksi = $query->count();
        $this->totalProdukTerjual = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed')
            ->when($this->startDate, function($q) {
                $q->whereDate('transactions.created_at', '>=', $this->startDate);
            })
            ->when($this->endDate, function($q) {
                $q->whereDate('transactions.created_at', '<=', $this->endDate);
            })
            ->sum('transaction_details.quantity');
        
        $this->rataRataTransaksi = $this->totalTransaksi > 0 ? $this->totalPenjualan / $this->totalTransaksi : 0;
        
        $this->transactions = $query->with('details.product', 'user')->orderBy('created_at', 'desc')->get();
    }
    
    public function loadChartData()
    {
        $start = $this->startDate ?? now()->startOfMonth();
        $end = $this->endDate ?? now();
        
        $salesData = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $this->chartData = [
            'labels' => $salesData->pluck('date')->map(function($date) {
                return date('d/m/Y', strtotime($date));
            }),
            'values' => $salesData->pluck('total')
        ];
    }
    
    public function updatedStartDate()
    {
        $this->loadData();
        $this->loadChartData();
        $this->dispatch('refreshChart');
    }
    
    public function updatedEndDate()
    {
        $this->loadData();
        $this->loadChartData();
        $this->dispatch('refreshChart');
    }
    
    public function getTopProductsProperty()
    {
        return DB::table('transaction_details')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed')
            ->when($this->startDate, function($q) {
                $q->whereDate('transactions.created_at', '>=', $this->startDate);
            })
            ->when($this->endDate, function($q) {
                $q->whereDate('transactions.created_at', '<=', $this->endDate);
            })
            ->select('products.name', DB::raw('SUM(transaction_details.quantity) as total_terjual'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();
    }
    
    public function exportExcel()
    {
        return Excel::download(new LaporanPenjualanExport($this->startDate, $this->endDate), 'laporan-penjualan-' . now()->format('Y-m-d') . '.xlsx');
    }
    
    protected function getListeners()
    {
        return [
            'refreshChart' => '$refresh',
        ];
    }
    
    public function refreshData()
    {
        $this->loadData();
        $this->loadChartData();
        $this->dispatch('refreshChart');
    }
}