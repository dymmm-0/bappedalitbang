<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class Kunjungan_Controller extends Controller
{
    public function index()
    {
        $daily = \DB::table('visits')
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
            ->whereNotNull('created_at')
            ->groupBy('tanggal')
            ->orderByDesc('tanggal')
            ->limit(14)
            ->get();

        $total = \DB::table('visits')->count();
        $today = (int) \DB::table('visits')
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return view('admin.kunjungan.index', compact('daily', 'total', 'today'));
    }

    /**
     * Sumber data grafik tren kunjungan (real data dari tabel `visits`,
     * bukan dummy). Periode: today | 7d | 30d | month | year.
     */
    public function chartData(Request $request)
    {
        $periode = (string) $request->query('periode', '7d');

        if (! in_array($periode, ['today', '7d', '30d', 'month', 'year'], true)) {
            $periode = '7d';
        }

        [$labels, $values] = $this->seriesFor($periode);

        return response()->json([
            'periode' => $periode,
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values),
        ]);
    }

    private function seriesFor(string $periode): array
    {
        $now = Carbon::now();

        if ($periode === 'today') {
            $byHour = \DB::table('visits')
                ->whereDate('created_at', $now->toDateString())
                ->selectRaw('HOUR(created_at) as grp, COUNT(*) as jml')
                ->groupBy('grp')
                ->pluck('jml', 'grp');

            $labels = [];
            $values = [];
            for ($h = 0; $h <= 23; $h++) {
                $labels[] = sprintf('%02d:00', $h);
                $values[] = (int) ($byHour[$h] ?? 0);
            }

            return [$labels, $values];
        }

        if ($periode === '7d') {
            return $this->dailySeries($now->copy()->startOfDay()->subDays(6), $now->copy()->startOfDay());
        }

        if ($periode === '30d') {
            return $this->dailySeries($now->copy()->startOfDay()->subDays(29), $now->copy()->startOfDay());
        }

        if ($periode === 'month') {
            return $this->dailySeries($now->copy()->startOfMonth()->startOfDay(), $now->copy()->startOfDay());
        }

        // year → seri per bulan (interval sesuai data yang tersedia).
        $byMonth = \DB::table('visits')
            ->whereYear('created_at', $now->year)
            ->selectRaw('MONTH(created_at) as grp, COUNT(*) as jml')
            ->groupBy('grp')
            ->pluck('jml', 'grp');

        $nama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $labels = [];
        $values = [];
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = $nama[$m - 1];
            $values[] = (int) ($byMonth[$m] ?? 0);
        }

        return [$labels, $values];
    }

    /**
     * Seri harian berurutan (hari tanpa kunjungan = 0) antara from..to.
     */
    private function dailySeries(Carbon $from, Carbon $to): array
    {
        $byDay = \DB::table('visits')
            ->whereBetween('created_at', [$from->toDateTimeString(), $to->copy()->endOfDay()->toDateTimeString()])
            ->selectRaw('DATE(created_at) as grp, COUNT(*) as jml')
            ->groupBy('grp')
            ->pluck('jml', 'grp');

        $labels = [];
        $values = [];
        for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
            $labels[] = $d->format('d M');
            $values[] = (int) ($byDay[$d->format('Y-m-d')] ?? 0);
        }

        return [$labels, $values];
    }
}