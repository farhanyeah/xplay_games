<?php

namespace App\Console\Commands;

use App\Models\Sewa;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CancelExpiredSewa extends Command
{
    protected $signature = 'sewa:cancel-expired 
                            {--hours=1 : Jumlah jam sebelum dibatalkan}';

    protected $description = 'Membatalkan pesanan sewa yang pending lebih dari batas waktu';

    public function handle()
    {
        $hours = (int) $this->option('hours');
        
        // Hitung batas waktu
        $batasWaktu = Carbon::now('Asia/Jakarta')
            ->subHours($hours);

        // Cari pesanan pending yang sudah melampaui batas
        $expiredSewa = Sewa::where('status_sewa', 'pending')
            ->where('payment_status', 'unpaid')
            ->where('created_at', '<', $batasWaktu)
            ->get();

        $count = $expiredSewa->count();

        if ($count > 0) {
            // Update status
            foreach ($expiredSewa as $sewa) {
                $sewa->update([
                    'status_sewa' => 'cancelled'
                ]);
            }

            $this->info("Berhasil membatalkan {$count} pesanan sewa yang expired.");
        } else {
            $this->info('Tidak ada pesanan yang expired.');
        }

        return 0;
    }
}