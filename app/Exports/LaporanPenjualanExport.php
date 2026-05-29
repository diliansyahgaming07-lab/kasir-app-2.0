<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPenjualanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Transaction::with('details.product', 'user')->where('status', 'completed');
        
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Invoice',
            'Tanggal',
            'Pelanggan',
            'Produk',
            'Qty',
            'Harga',
            'Subtotal',
            'Total',
            'Metode Bayar',
            'Kasir'
        ];
    }

    public function map($transaction): array
    {
        $rows = [];
        $no = 1;
        
        foreach ($transaction->details as $detail) {
            $rows[] = [
                $no++,
                $transaction->invoice_number,
                $transaction->created_at->format('d/m/Y H:i'),
                $transaction->customer_name,
                $detail->product->name,
                $detail->quantity,
                'Rp ' . number_format($detail->price, 0, ',', '.'),
                'Rp ' . number_format($detail->subtotal, 0, ',', '.'),
                'Rp ' . number_format($transaction->total_amount, 0, ',', '.'),
                $this->getPaymentMethod($transaction->payment_method),
                $transaction->user->name ?? 'Admin'
            ];
        }
        
        return $rows;
    }

    private function getPaymentMethod($method)
    {
        return [
            'cash' => 'Tunai',
            'qris' => 'QRIS',
            'debit' => 'Debit',
            'credit' => 'Kredit'
        ][$method] ?? $method;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}