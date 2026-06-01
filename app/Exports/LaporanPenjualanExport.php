<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LaporanPenjualanExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithColumnWidths,
    WithTitle,
    WithCustomStartCell,
    WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $totalRows = 0;
    protected $grandTotal = 0;
    protected $totalQty = 0;

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
        
        $transactions = $query->orderBy('created_at', 'desc')->get();
        
        // Hitung total untuk summary
        $this->grandTotal = $transactions->sum('total_amount');
        $this->totalQty = $transactions->sum(function($t) {
            return $t->details->sum('quantity');
        });
        $this->totalRows = $transactions->sum(function($t) {
            return $t->details->count();
        });
        
        return $transactions;
    }

    public function headings(): array
    {
        return [
            'No',
            'Invoice',
            'Tanggal',
            'Jam',
            'Pelanggan',
            'Produk',
            'Kode Produk',
            'Qty',
            'Harga Satuan',
            'Subtotal',
            'Total Transaksi',
            'Metode Bayar',
            'Kasir',
            'Status'
        ];
    }

    public function map($transaction): array
    {
        $rows = [];
        $no = 1;
        $firstItem = true;
        
        foreach ($transaction->details as $detail) {
            $rows[] = [
                $no++,
                $transaction->invoice_number ?? 'INV-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT),
                $transaction->created_at->format('d/m/Y'),
                $transaction->created_at->format('H:i:s'),
                $transaction->customer_name ?? 'Umum',
                $detail->product->name,
                $detail->product->sku ?? '-',
                $detail->quantity,
                $detail->price,
                $detail->subtotal,
                $firstItem ? $transaction->total_amount : '',
                $firstItem ? $this->getPaymentMethod($transaction->payment_method) : '',
                $firstItem ? ($transaction->user->name ?? 'Admin') : '',
                $firstItem ? 'Completed' : ''
            ];
            $firstItem = false;
        }
        
        // Jika tidak ada detail produk (seharusnya tidak terjadi)
        if (empty($rows)) {
            $rows[] = [
                1,
                $transaction->invoice_number ?? 'INV-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT),
                $transaction->created_at->format('d/m/Y'),
                $transaction->created_at->format('H:i:s'),
                $transaction->customer_name ?? 'Umum',
                '-',
                '-',
                0,
                0,
                0,
                $transaction->total_amount,
                $this->getPaymentMethod($transaction->payment_method),
                $transaction->user->name ?? 'Admin',
                'Completed'
            ];
        }
        
        return $rows;
    }

    private function getPaymentMethod($method)
    {
        return [
            'cash' => '💵 Tunai',
            'qris' => '📱 QRIS',
            'debit' => '💳 Debit',
            'credit' => '💎 Kredit'
        ][$method] ?? $method;
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A5:N5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);
        
        // Style untuk baris data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A6:N' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E0E0E0']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        
        // Format number untuk kolom harga, subtotal, total
        $sheet->getStyle('I6:J' . $lastRow)->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getStyle('K6:K' . $lastRow)->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        
        // Zebra striping
        for ($row = 6; $row <= $lastRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':N' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F9F9F9');
            }
        }
        
        // Style untuk summary section
        $sheet->getStyle('A1:N4')->getFont()->setSize(10);
        $sheet->getStyle('A2:B2')->getFont()->setBold(true);
        $sheet->getStyle('A4:B4')->getFont()->setBold(true);
        
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,      // No
            'B' => 18,     // Invoice
            'C' => 12,     // Tanggal
            'D' => 10,     // Jam
            'E' => 20,     // Pelanggan
            'F' => 35,     // Produk
            'G' => 15,     // Kode Produk
            'H' => 8,      // Qty
            'I' => 15,     // Harga Satuan
            'J' => 15,     // Subtotal
            'K' => 18,     // Total Transaksi
            'L' => 15,     // Metode Bayar
            'M' => 20,     // Kasir
            'N' => 12,     // Status
        ];
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Header Laporan
                $sheet->setCellValue('A1', 'TOKO ANDA');
                $sheet->setCellValue('A2', 'LAPORAN PENJUALAN');
                $sheet->setCellValue('A3', 'Periode: ' . $this->formatDate($this->startDate) . ' - ' . $this->formatDate($this->endDate));
                $sheet->setCellValue('A4', 'Tanggal Cetak: ' . now()->format('d/m/Y H:i:s'));
                
                // Merge cells untuk header
                $sheet->mergeCells('A1:N1');
                $sheet->mergeCells('A2:N2');
                $sheet->mergeCells('A3:N3');
                $sheet->mergeCells('A4:N4');
                
                // Style header laporan
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['size' => 14, 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['size' => 16, 'bold' => true, 'color' => ['rgb' => '4F81BD']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
                $sheet->getStyle('A3:A4')->applyFromArray([
                    'font' => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
                
                // Auto filter
                $sheet->setAutoFilter('A5:N5');
                
                // Freeze pane
                $sheet->freezePane('A6');
                
                // Summary Footer
                $lastRow = $sheet->getHighestRow() + 2;
                $summaryRow = $lastRow;
                
                $sheet->setCellValue('A' . $summaryRow, 'SUMMARY');
                $sheet->mergeCells('A' . $summaryRow . ':N' . $summaryRow);
                $sheet->getStyle('A' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E8F0FE']
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
                
                $summaryRow++;
                $sheet->setCellValue('A' . $summaryRow, 'Total Transaksi:');
                $sheet->setCellValue('B' . $summaryRow, $this->getTotalTransactions());
                $sheet->mergeCells('C' . $summaryRow . ':N' . $summaryRow);
                
                $summaryRow++;
                $sheet->setCellValue('A' . $summaryRow, 'Total Item Terjual:');
                $sheet->setCellValue('B' . $summaryRow, $this->totalQty);
                $sheet->mergeCells('C' . $summaryRow . ':N' . $summaryRow);
                
                $summaryRow++;
                $sheet->setCellValue('A' . $summaryRow, 'Total Pendapatan:');
                $sheet->setCellValue('B' . $summaryRow, 'Rp ' . number_format($this->grandTotal, 0, ',', '.'));
                $sheet->mergeCells('C' . $summaryRow . ':N' . $summaryRow);
                
                $summaryRow++;
                $sheet->setCellValue('A' . $summaryRow, 'Rata-rata Transaksi:');
                $sheet->setCellValue('B' . $summaryRow, 'Rp ' . number_format($this->getAverageTransaction(), 0, ',', '.'));
                $sheet->mergeCells('C' . $summaryRow . ':N' . $summaryRow);
                
                // Style summary
                $sheet->getStyle('A' . ($summaryRow - 3) . ':B' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F5F5F5']
                    ]
                ]);
                
                // Set tinggi baris
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(30);
                $sheet->getRowDimension(5)->setRowHeight(20);
            },
        ];
    }
    
    private function formatDate($date)
    {
        if (!$date) return 'Semua Tanggal';
        return date('d/m/Y', strtotime($date));
    }
    
    private function getTotalTransactions()
    {
        $query = Transaction::where('status', 'completed');
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        return $query->count();
    }
    
    private function getAverageTransaction()
    {
        $totalTrans = $this->getTotalTransactions();
        if ($totalTrans == 0) return 0;
        return $this->grandTotal / $totalTrans;
    }
}