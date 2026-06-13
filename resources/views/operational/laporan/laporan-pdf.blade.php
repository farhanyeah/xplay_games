<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan XPLAY - {{ $laporan->tanggal }}</title>
    <style>
        @page { size: A4; margin: 15mm; }
        
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            font-size: 10px; 
            color: #333;
            line-height: 1.3;
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 12px; 
            padding-bottom: 8px; 
            border-bottom: 2px solid #333;
        }
        .header h1 { font-size: 16px; font-weight: bold; margin: 0; }
        .header p { font-size: 11px; margin: 2px 0 0 0; color: #555; }
        
        .meta {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            margin-bottom: 12px;
            padding: 4px 8px;
            background: #f5f5f5;
            border-radius: 2px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 10px;
        }
        th, td { 
            padding: 5px 6px; 
            border: 1px solid #999; 
            font-size: 10px;
        }
        
        th { 
            background: #6c757d; 
            color: #fff; 
            text-align: left;
            font-weight: 600;
        }
        
        .text-right { text-align: right; }
        
        .bg-total { background: #e9ecef; }
        .text-bold { font-weight: bold; }
        
        .footer { 
            margin-top: 12px; 
            text-align: center; 
            font-size: 8px; 
            color: #888;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN HARIAN XPLAY GAMES</h1>
        <p>{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</p>
    </div>

    <div class="meta">
        <span>Petugas: {{ $laporan->createdBy->name ?? '-' }}</span>
        <span>{{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d F Y, H:i') }}</span>
    </div>

    <!-- PENDAPATAN -->
    <table>
        <tr>
            <th colspan="2">PENDAPATAN</th>
            <th class="text-right">Rp</th>
        </tr>
        <tr><td width="12">1.</td><td>Billing</td><td class="text-right">{{ number_format($laporan->pendapatan_billing, 0, ',', '.') }}</td></tr>
        <tr><td>2.</td><td>Sewa</td><td class="text-right">{{ number_format($laporan->pendapatan_sewa, 0, ',', '.') }}</td></tr>
        <tr><td>3.</td><td>Booking</td><td class="text-right">{{ number_format($laporan->pendapatan_booking, 0, ',', '.') }}</td></tr>
        <tr><td>4.</td><td>Penjualan</td><td class="text-right">{{ number_format($laporan->pendapatan_penjualan, 0, ',', '.') }}</td></tr>
        <tr class="bg-total"><td colspan="2"><strong>TOTAL PENDAPATAN</strong></td><td class="text-right text-bold">{{ number_format($laporan->total_pendapatan, 0, ',', '.') }}</td></tr>
    </table>

    <!-- PENGELUARAN -->
    @php $totalPengeluaran = $laporan->pengeluaran_part_time + $laporan->pengeluaran_gestun + $laporan->pengeluaran_lain; @endphp
    <table>
        <tr>
            <th colspan="2">PENGELUARAN</th>
            <th class="text-right">Rp</th>
        </tr>
        <tr><td width="12">1.</td><td>Uang Part Time</td><td class="text-right">{{ number_format($laporan->pengeluaran_part_time, 0, ',', '.') }}</td></tr>
        <tr><td>2.</td><td>Gestun</td><td class="text-right">{{ number_format($laporan->pengeluaran_gestun, 0, ',', '.') }}</td></tr>
        <tr><td>3.</td><td>Lainnya</td><td class="text-right">{{ number_format($laporan->pengeluaran_lain, 0, ',', '.') }}</td></tr>
        <tr class="bg-total"><td colspan="2"><strong>TOTAL PENGELUARAN</strong></td><td class="text-right text-bold">{{ number_format($totalPengeluaran, 0, ',', '.') }}</td></tr>
        @if($laporan->keterangan_pengeluaran)
        <tr><td colspan="3"><em>Ket: {{ $laporan->keterangan_pengeluaran }}</em></td></tr>
        @endif
    </table>

    <!-- KAS -->
    <table>
        <tr>
            <th colspan="2">KAS</th>
            <th class="text-right">Rp</th>
        </tr>
        <tr><td width="12">1.</td><td>Buka Kas (Cash Kemarin)</td><td class="text-right">{{ number_format($laporan->buka_kas, 0, ',', '.') }}</td></tr>
        <tr><td>2.</td><td>Total Pendapatan</td><td class="text-right">{{ number_format($laporan->total_pendapatan, 0, ',', '.') }}</td></tr>
        <tr><td>3.</td><td>Total Pengeluaran</td><td class="text-right">{{ number_format($totalPengeluaran, 0, ',', '.') }}</td></tr>
        <tr><td>4.</td><td>Saldo Midtrans (Transfer)</td><td class="text-right">{{ number_format($laporan->saldo_midtrans, 0, ',', '.') }}</td></tr>
        <tr class="bg-total"><td colspan="2"><strong>TUTUP KAS (Cash di Tangan)</strong></td><td class="text-right text-bold">{{ number_format($laporan->tutup_kas, 0, ',', '.') }}</td></tr>
    </table>

    <div class="footer">
        XPLAY GAMES {{ date('Y') }}
    </div>
</body>
</html>