<x-filament::page>
    {{-- SATU ROOT ELEMENT - SEMUA KONTEN DALAM SATU DIV --}}
    <div>
        <div class="report-dashboard space-y-6">
            {{-- Header Premium --}}
            <div class="report-gradient rounded-2xl p-6 shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 group-hover:scale-150 transition-all duration-700"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/10 rounded-full -ml-32 -mb-32 group-hover:scale-150 transition-all duration-700"></div>
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold flex items-center gap-2 text-white">
                            📊 Dashboard Laporan Premium
                            <span class="text-xs bg-white/20 px-2 py-1 rounded-full text-white">Real-time Analytics</span>
                        </h1>
                        <p class="text-white/90 mt-2">Analisis penjualan dan performa bisnis Anda secara mendalam</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-white">{{ now()->format('d F Y') }}</div>
                        <div class="text-sm text-white/70">{{ now()->format('H:i') }} WIB</div>
                    </div>
                </div>
            </div>

            {{-- Filter Tanggal Premium --}}
            <div class="glass-effect rounded-2xl shadow-xl p-6 border border-gray-100 backdrop-blur-sm no-print">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-1 h-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                    <h2 class="text-xl font-bold text-premium-dark">📅 Filter Laporan</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-premium-dark mb-2">📆 Dari Tanggal</label>
                        <input type="date" wire:model.live="startDate" class="w-full px-4 py-3 border-2 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all text-premium-dark bg-white border-gray-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-premium-dark mb-2">📆 Sampai Tanggal</label>
                        <input type="date" wire:model.live="endDate" class="w-full px-4 py-3 border-2 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all text-premium-dark bg-white border-gray-200">
                    </div>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <div class="flex gap-2">
                        <button wire:click="setFilterToday" class="bg-gray-100 hover:bg-gray-200 text-premium-dark px-4 py-2 rounded-xl transition-all text-sm">📍 Hari Ini</button>
                        <button wire:click="setFilterThisWeek" class="bg-gray-100 hover:bg-gray-200 text-premium-dark px-4 py-2 rounded-xl transition-all text-sm">📅 Minggu Ini</button>
                        <button wire:click="setFilterThisMonth" class="bg-gray-100 hover:bg-gray-200 text-premium-dark px-4 py-2 rounded-xl transition-all text-sm">📆 Bulan Ini</button>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="refreshData" class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-5 py-2 rounded-xl hover:shadow-lg transition-all flex items-center gap-2">🔄 Refresh</button>
                        <button wire:click="exportExcel" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-5 py-2 rounded-xl hover:shadow-lg transition-all flex items-center gap-2">📊 Export Excel</button>
                        <button onclick="window.print()" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-5 py-2 rounded-xl hover:shadow-lg transition-all flex items-center gap-2">🖨️ Print</button>
                    </div>
                </div>
            </div>

            {{-- Statistik Card --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="stat-card gradient-blue rounded-2xl p-6 shadow-xl cursor-pointer">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-5xl animate-float">💰</div>
                        <div class="text-right">
                            <div class="text-sm text-white/80">Total Pendapatan</div>
                            <div class="text-2xl font-bold text-white">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-white/80">✓ Termasuk PPN 11%</div>
                    <div class="mt-3 h-1 bg-white/30 rounded-full overflow-hidden">
                        <div class="w-full h-full bg-white rounded-full animate-pulse"></div>
                    </div>
                </div>
                
                <div class="stat-card gradient-orange rounded-2xl p-6 shadow-xl cursor-pointer">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-5xl">🧾</div>
                        <div class="text-right">
                            <div class="text-sm text-white/80">Total Transaksi</div>
                            <div class="text-2xl font-bold text-white">{{ number_format($totalTransaksi) }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-white/80">✓ Transaksi selesai</div>
                    <div class="mt-3 h-1 bg-white/30 rounded-full overflow-hidden">
                        <div class="w-full h-full bg-white rounded-full"></div>
                    </div>
                </div>
                
                <div class="stat-card gradient-purple rounded-2xl p-6 shadow-xl cursor-pointer">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-5xl">📦</div>
                        <div class="text-right">
                            <div class="text-sm text-white/80">Produk Terjual</div>
                            <div class="text-2xl font-bold text-white">{{ number_format($totalProdukTerjual) }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-white/80">✓ Unit produk</div>
                    <div class="mt-3 h-1 bg-white/30 rounded-full overflow-hidden">
                        <div class="w-full h-full bg-white rounded-full"></div>
                    </div>
                </div>
                
                <div class="stat-card gradient-green rounded-2xl p-6 shadow-xl cursor-pointer">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-5xl">⭐</div>
                        <div class="text-right">
                            <div class="text-sm text-white/80">Rata-rata Transaksi</div>
                            <div class="text-2xl font-bold text-white">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-white/80">✓ Per transaksi</div>
                    <div class="mt-3 h-1 bg-white/30 rounded-full overflow-hidden">
                        <div class="w-full h-full bg-white rounded-full"></div>
                    </div>
                </div>
            </div>

            {{-- GRAFIK PENJUALAN --}}
            <div class="glass-effect rounded-2xl shadow-xl p-6 border border-gray-100 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500"></div>
                        <h2 class="text-xl font-bold text-premium-dark">📈 Grafik Penjualan Harian</h2>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="changeChartType('line')" class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-lg">📈 Line</button>
                        <button onclick="changeChartType('bar')" class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-lg">📊 Bar</button>
                    </div>
                </div>
                <div style="min-height: 350px;">
                    <canvas id="salesChart" style="max-height: 350px; width: 100%;"></canvas>
                </div>
            </div>

            {{-- Top Produk --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="glass-effect rounded-2xl shadow-xl p-6 border border-gray-100 backdrop-blur-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-8 rounded-full bg-gradient-to-r from-orange-500 to-red-500"></div>
                        <h2 class="text-xl font-bold text-premium-dark">🏆 Top 5 Produk Terlaris</h2>
                    </div>
                    <div class="space-y-4">
                        @forelse($this->topProducts as $index => $product)
                            <div class="group flex justify-between items-center p-4 bg-white rounded-xl hover:shadow-lg transition-all cursor-pointer border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center font-bold text-white shadow-md">{{ $index + 1 }}</div>
                                    <span class="font-semibold text-premium-dark">{{ $product->name }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-500 group-hover:scale-x-110" style="width: {{ min(100, ($product->total_terjual / max($this->topProducts->first()->total_terjual ?? 1, 1)) * 100) }}%"></div>
                                    </div>
                                    <span class="text-premium-purple font-bold">{{ number_format($product->total_terjual) }} terjual</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-premium-light text-center py-8">Belum ada data penjualan</p>
                        @endforelse
                    </div>
                </div>

                <div class="glass-effect rounded-2xl shadow-xl p-6 border border-gray-100 backdrop-blur-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-8 rounded-full bg-gradient-to-r from-green-500 to-blue-500"></div>
                        <h2 class="text-xl font-bold text-premium-dark">💳 Metode Pembayaran</h2>
                    </div>
                    <div class="space-y-4">
                        @php
                            $cashCount = $transactions->where('payment_method', 'cash')->count();
                            $qrisCount = $transactions->where('payment_method', 'qris')->count();
                            $debitCount = $transactions->where('payment_method', 'debit')->count();
                            $creditCount = $transactions->where('payment_method', 'credit')->count();
                            $total = max($cashCount + $qrisCount + $debitCount + $creditCount, 1);
                        @endphp
                        
                        <div class="group cursor-pointer">
                            <div class="flex justify-between mb-2">
                                <span class="font-semibold text-premium-dark">💵 Tunai</span>
                                <span class="text-premium-light">{{ number_format($cashCount) }} transaksi ({{ round(($cashCount / $total) * 100) }}%)</span>
                            </div>
                            <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full gradient-green rounded-full transition-all duration-500 group-hover:scale-x-110" style="width: {{ ($cashCount / $total) * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="group cursor-pointer">
                            <div class="flex justify-between mb-2">
                                <span class="font-semibold text-premium-dark">📱 QRIS</span>
                                <span class="text-premium-light">{{ number_format($qrisCount) }} transaksi ({{ round(($qrisCount / $total) * 100) }}%)</span>
                            </div>
                            <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full gradient-blue rounded-full transition-all duration-500 group-hover:scale-x-110" style="width: {{ ($qrisCount / $total) * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="group cursor-pointer">
                            <div class="flex justify-between mb-2">
                                <span class="font-semibold text-premium-dark">💳 Debit</span>
                                <span class="text-premium-light">{{ number_format($debitCount) }} transaksi ({{ round(($debitCount / $total) * 100) }}%)</span>
                            </div>
                            <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full gradient-orange rounded-full transition-all duration-500 group-hover:scale-x-110" style="width: {{ ($debitCount / $total) * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="group cursor-pointer">
                            <div class="flex justify-between mb-2">
                                <span class="font-semibold text-premium-dark">💎 Kredit</span>
                                <span class="text-premium-light">{{ number_format($creditCount) }} transaksi ({{ round(($creditCount / $total) * 100) }}%)</span>
                            </div>
                            <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full gradient-purple rounded-full transition-all duration-500 group-hover:scale-x-110" style="width: {{ ($creditCount / $total) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Transaksi --}}
            <div class="glass-effect rounded-2xl shadow-xl p-6 border border-gray-100 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                        <h2 class="text-xl font-bold text-premium-dark">📋 Riwayat Transaksi</h2>
                    </div>
                    <div class="text-sm text-premium-light bg-gray-100 px-3 py-1 rounded-full">Total {{ number_format($transactions->count()) }} transaksi</div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-100 to-gray-50 rounded-xl">
                                <th class="p-4 text-left text-sm font-semibold text-premium-dark">Invoice</th>
                                <th class="p-4 text-left text-sm font-semibold text-premium-dark">Tanggal</th>
                                <th class="p-4 text-left text-sm font-semibold text-premium-dark">Pelanggan</th>
                                <th class="p-4 text-right text-sm font-semibold text-premium-dark">Total</th>
                                <th class="p-4 text-left text-sm font-semibold text-premium-dark">Metode</th>
                                <th class="p-4 text-left text-sm font-semibold text-premium-dark">Kasir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr class="table-row border-b cursor-pointer hover:bg-gray-50">
                                    <td class="p-4 font-semibold text-premium-purple">{{ $transaction->invoice_number ?? 'INV-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="p-4 text-premium-light">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="p-4 font-medium text-premium-dark">{{ $transaction->customer_name ?? 'Umum' }}</td>
                                    <td class="p-4 text-right font-bold text-premium-dark">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    <td class="p-4">
                                        @if($transaction->payment_method == 'cash')
                                            <span class="badge-cash text-white px-3 py-1 rounded-full text-xs font-semibold shadow-md">💵 Tunai</span>
                                        @elseif($transaction->payment_method == 'qris')
                                            <span class="badge-qris text-white px-3 py-1 rounded-full text-xs font-semibold shadow-md">📱 QRIS</span>
                                        @elseif($transaction->payment_method == 'debit')
                                            <span class="badge-debit text-white px-3 py-1 rounded-full text-xs font-semibold shadow-md">💳 Debit</span>
                                        @else
                                            <span class="badge-credit text-white px-3 py-1 rounded-full text-xs font-semibold shadow-md">💎 Kredit</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-premium-light">{{ $transaction->user->name ?? 'Admin' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center">
                                        <div class="text-6xl mb-3">📭</div>
                                        <p class="text-premium-dark">Belum ada transaksi dalam periode ini</p>
                                        <p class="text-sm text-premium-light mt-2">Coba ubah filter tanggal atau lakukan transaksi terlebih dahulu</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <style>
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes glow {
                0%, 100% { box-shadow: 0 0 5px rgba(102, 126, 234, 0.3); }
                50% { box-shadow: 0 0 20px rgba(102, 126, 234, 0.6); }
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-5px); }
            }
            
            .report-dashboard .stat-card {
                animation: fadeInUp 0.5s ease-out;
                transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                position: relative;
                overflow: hidden;
            }
            .report-dashboard .stat-card:hover {
                transform: translateY(-8px) scale(1.02);
                animation: glow 1.5s infinite;
            }
            .report-dashboard .stat-card::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
                transform: rotate(45deg);
                transition: all 0.5s;
            }
            .report-dashboard .stat-card:hover::before {
                transform: rotate(45deg) translate(10%, 10%);
            }
            .report-dashboard .report-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .report-dashboard .gradient-blue {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }
            .report-dashboard .gradient-green {
                background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            }
            .report-dashboard .gradient-orange {
                background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            }
            .report-dashboard .gradient-purple {
                background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            }
            .report-dashboard .table-row {
                transition: all 0.3s ease;
            }
            .report-dashboard .table-row:hover {
                background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
                transform: scale(1.01);
                box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            }
            .report-dashboard .badge-cash { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
            .report-dashboard .badge-qris { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
            .report-dashboard .badge-debit { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
            .report-dashboard .badge-credit { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); }
            .report-dashboard .glass-effect {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
            }
            .report-dashboard .text-premium-dark { color: #1e293b; }
            .report-dashboard .text-premium-light { color: #64748b; }
            .report-dashboard .text-premium-purple { color: #8b5cf6; }
            @media print {
                .report-dashboard .no-print { display: none; }
                .report-dashboard .glass-effect { background: white; box-shadow: none; border: 1px solid #ddd; }
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            let salesChart = null;
            let currentChartType = 'line';
            
            function initChart() {
                const canvas = document.getElementById('salesChart');
                if (!canvas) return;
                
                if (salesChart) {
                    salesChart.destroy();
                    salesChart = null;
                }
                
                const labels = @json($chartData['labels'] ?? []);
                const values = @json($chartData['values'] ?? []);
                
                if (!labels || labels.length === 0) {
                    canvas.style.display = 'none';
                    return;
                }
                
                canvas.style.display = 'block';
                
                salesChart = new Chart(canvas, {
                    type: currentChartType,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Penjualan (Rp)',
                            data: values,
                            borderColor: 'rgb(102, 126, 234)',
                            backgroundColor: currentChartType === 'bar' ? 'rgba(102, 126, 234, 0.7)' : 'rgba(102, 126, 234, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: currentChartType === 'line',
                            pointBackgroundColor: 'rgb(102, 126, 234)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: currentChartType === 'line' ? 5 : 0,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return '💰 Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            function changeChartType(type) {
                currentChartType = type;
                initChart();
            }
            
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(initChart, 200);
            });
            
            document.addEventListener('livewire:navigated', function() {
                setTimeout(initChart, 150);
            });
            
            window.addEventListener('refreshChart', function() {
                setTimeout(initChart, 150);
            });
        </script>
    </div>
</x-filament::page>