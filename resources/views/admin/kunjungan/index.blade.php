@extends('admin.layouts.master')
@section('title', 'Kunjungan')
@section('page-title', 'Kunjungan')
@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 mb-6">
    <div class="card p-4 md:p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
            </div>
            <div>
                <p class="text-2xl font-heading font-bold text-primary">{{ number_format($total) }}</p>
                <p class="text-xs text-slate-500">Total Kunjungan Website</p>
            </div>
        </div>
    </div>
    <div class="card p-4 md:p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gold/10 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-heading font-bold text-primary">{{ number_format($today) }}</p>
                <p class="text-xs text-slate-500">Kunjungan Hari Ini</p>
            </div>
        </div>
    </div>
</div>

<div class="card p-0 mb-6">
    <div class="p-4 md:p-6 border-b border-border flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-heading font-bold text-primary">Tren Kunjungan</h2>
            <p class="text-sm text-slate-500 mt-0.5">Total pada periode terpilih: <strong id="periodeTotal" class="text-primary tabular-nums">0</strong> kunjungan (data real dari sistem kunjungan).</p>
        </div>
        <div class="flex flex-wrap gap-1.5" role="group" aria-label="Periode grafik">
            <button type="button" data-periode="today" class="px-3 py-1.5 text-xs font-semibold rounded-full border border-border bg-white text-slate-600 hover:border-accent transition-colors">Hari Ini</button>
            <button type="button" data-periode="7d" class="px-3 py-1.5 text-xs font-semibold rounded-full border border-accent bg-accent text-white transition-colors">7 Hari Terakhir</button>
            <button type="button" data-periode="30d" class="px-3 py-1.5 text-xs font-semibold rounded-full border border-border bg-white text-slate-600 hover:border-accent transition-colors">30 Hari Terakhir</button>
            <button type="button" data-periode="month" class="px-3 py-1.5 text-xs font-semibold rounded-full border border-border bg-white text-slate-600 hover:border-accent transition-colors">Bulan Ini</button>
            <button type="button" data-periode="year" class="px-3 py-1.5 text-xs font-semibold rounded-full border border-border bg-white text-slate-600 hover:border-accent transition-colors">Tahun Ini</button>
        </div>
    </div>
    <div class="p-4 md:p-6">
        <div class="relative h-72 md:h-80">
            <canvas id="visitsChart" style="max-width:100%;"></canvas>
        </div>
    </div>
</div>

<div class="card p-0">
    <div class="p-4 md:p-6 border-b border-border flex items-center justify-between">
        <h2 class="text-lg font-heading font-bold text-primary">Kunjungan Harian (14 hari terakhir)</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-left">
                    <th class="px-4 py-3 font-semibold text-slate-600">#</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Tanggal</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Kunjungan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($daily as $index => $d)
                <tr class="border-t border-border hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ date('d M Y', strtotime($d->tanggal)) }}</td>
                    <td class="px-4 py-3 font-medium text-primary">{{ number_format($d->jumlah) }}</td>
                </tr>
                @empty
                <tr class="border-t border-border">
                    <td class="px-4 py-3 text-slate-400" colspan="3">Belum ada kunjungan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('visitsChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const chart = new Chart(canvas, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Kunjungan',
                data: [],
                borderColor: '#0369A1',
                backgroundColor: 'rgba(3, 105, 161, 0.12)',
                fill: true,
                tension: 0.35,
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: '#0369A1',
                pointHoverRadius: 5,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleColor: '#F8FAFC',
                    bodyColor: '#F8FAFC',
                    callbacks: {
                        label: function (item) {
                            return ' Kunjungan: ' + Number(item.parsed.y).toLocaleString('id-ID');
                        },
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748B', font: { family: 'Lato, sans-serif', size: 11 }, maxRotation: 45 },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.2)' },
                    ticks: { color: '#64748B', precision: 0, font: { family: 'Lato, sans-serif', size: 11 } },
                },
            },
        },
    });

    const btns = document.querySelectorAll('[data-periode]');
    const totalNote = document.getElementById('periodeTotal');

    async function loadChart(periode) {
        try {
            const res = await fetch('{{ url('admin/kunjungan/data') }}?periode=' + periode, {
                headers: { 'Accept': 'application/json' },
            });
            const d = await res.json();
            chart.data.labels = d.labels;
            chart.data.datasets[0].data = d.values;
            chart.update();
            totalNote.textContent = Number(d.total).toLocaleString('id-ID');

            btns.forEach(function (b) {
                const active = b.dataset.periode === d.periode;
                b.classList.toggle('bg-accent', active);
                b.classList.toggle('text-white', active);
                b.classList.toggle('border-accent', active);
                b.classList.toggle('bg-white', !active);
                b.classList.toggle('text-slate-600', !active);
                b.classList.toggle('border-border', !active);
                b.setAttribute('aria-pressed', active ? 'true' : 'false');
            });
        } catch (e) {
            totalNote.textContent = '—';
        }
    }

    btns.forEach(function (b) {
        b.addEventListener('click', function () { loadChart(b.dataset.periode); });
    });

    loadChart('7d');
});
</script>
@endpush