<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', 'Lucida Console', monospace;
            font-size: 12px;
            width: 58mm;
            margin: 0 auto;
            padding: 4px;
            background: white;
            color: black;
        }
        
        .struk {
            width: 100%;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-left {
            text-align: left;
        }
        
        .text-right {
            text-align: right;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .large {
            font-size: 16px;
        }
        
        .small {
            font-size: 10px;
        }
        
        .border-top {
            border-top: 1px dashed #000;
            margin: 5px 0;
            padding-top: 5px;
        }
        
        .border-bottom {
            border-bottom: 1px dashed #000;
            margin-bottom: 5px;
            padding-bottom: 5px;
        }
        
        .border-double {
            border-top: 2px double #000;
            margin: 5px 0;
        }
        
        .line {
            border-top: 1px dotted #000;
            margin: 4px 0;
        }
        
        .total {
            font-size: 14px;
            font-weight: bold;
        }
        
        .spacer {
            margin: 4px 0;
        }
        
        table {
            width: 100%;
            margin: 5px 0;
        }
        
        td {
            padding: 2px 0;
        }
        
        .product-name {
            font-size: 11px;
        }
        
        .thanks {
            margin: 10px 0;
            font-size: 12px;
        }
        
        .qr-code {
            text-align: center;
            margin: 10px 0;
        }
        
        .footer {
            margin-top: 10px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="struk">
        {{-- HEADER TOKO --}}
        <div class="text-center border-bottom">
            <h3 class="bold large">FOODHUB</h3>
            <p class="small">Jl. Contoh No. 123, Kota</p>
            <p class="small">Telp: 0812-3456-7890</p>
            <div class="line"></div>
        </div>

        {{-- INFO TRANSAKSI --}}
        <table width="100%">
            <tr>
                <td width="40%">Invoice</td>
                <td width="60%" class="text-right bold">{{ $transaction->invoice_number }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td class="text-right">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td class="text-right">{{ $transaction->user->name ?? 'Admin' }}</td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td class="text-right">{{ $transaction->customer_name }}</td>
            </tr>
            @if($transaction->member_id)
            <tr>
                <td>Member</td>
                <td class="text-right">✓ {{ $transaction->member->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Poin Digunakan</td>
                <td class="text-right">{{ number_format($transaction->points_used ?? 0) }} poin</td>
            </tr>
            <tr>
                <td>Poin Didapat</td>
                <td class="text-right">{{ number_format($transaction->points_earned ?? 0) }} poin</td>
            </tr>
            @endif
        </table>

        <div class="line"></div>

        {{-- DAFTAR PRODUK --}}
        <table width="100%">
            <thead>
                <tr>
                    <th class="text-left">Produk</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->details as $detail)
                <tr>
                    <td colspan="4" class="product-name">{{ $detail->product->name }}</td>
                </tr>
                <tr>
                    <td class="text-left"></td>
                    <td class="text-right">{{ $detail->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="line"></div>

        {{-- RINGKASAN PEMBAYARAN --}}
        <table width="100%">
            <tr>
                <td width="60%">Subtotal</td>
                <td width="40%" class="text-right">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
            </tr>
            
            @if($transaction->discount > 0)
            <tr>
                <td>Diskon</td>
                <td class="text-right">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            @if($transaction->points_used > 0)
            <tr>
                <td>Diskon Poin</td>
                <td class="text-right">- Rp {{ number_format($transaction->points_used * 100, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            @if($transaction->service_fee > 0)
            <tr>
                <td>Biaya Layanan</td>
                <td class="text-right">+ Rp {{ number_format($transaction->service_fee, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            <tr>
                <td>PPN 11%</td>
                <td class="text-right">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</td>
            </tr>
            
            <tr class="bold">
                <td class="large">TOTAL</td>
                <td class="text-right large">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
            </tr>
            
            <tr>
                <td>Bayar</td>
                <td class="text-right">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
            </tr>
            
            <tr class="bold">
                <td>Kembali</td>
                <td class="text-right">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="line"></div>

        {{-- METODE PEMBAYARAN --}}
        <table width="100%">
            <tr>
                <td width="50%">Metode Bayar</td>
                <td width="50%" class="text-right">
                    @if($transaction->payment_method == 'cash') 💵 TUNAI
                    @elseif($transaction->payment_method == 'qris') 📱 QRIS
                    @elseif($transaction->payment_method == 'debit') 💳 DEBIT
                    @else 💎 KREDIT
                    @endif
                </td>
            </tr>
        </table>

        @if($transaction->notes)
        <div class="border-top">
            <p class="small">📝 Catatan:</p>
            <p class="small">{{ $transaction->notes }}</p>
        </div>
        @endif

        {{-- FOOTER --}}
        <div class="border-top text-center">
            <p class="bold">Terima Kasih!</p>
            <p class="small">Silakan datang kembali</p>
            <div class="spacer"></div>
            <p class="small">{{ now()->format('d/m/Y H:i:s') }}</p>
            <p class="small">*** Barang yang sudah dibeli tidak dapat ditukar ***</p>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 1000);
        };
    </script>
</body>
</html>