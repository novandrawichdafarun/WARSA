<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Models\CommissionLedger;
use App\Models\Transaction;
use App\Services\LaporanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function __construct(protected LaporanService $laporanService)
    {
    }

    public function index(Request $request): View
    {
        $request->validate([
            'dari' => ['nullable', 'date', 'before_or_equal:sampai'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'preset' => ['nullable', 'in:hari_ini,minggu_ini,bulan_ini,bulan_lalu,tahun_ini'],
        ]);

        [$dari, $sampai] = $this->laporanService->parsePeriode(
            dari: $request->dari,
            sampai: $request->sampai,
            preset: $request->input('preset', 'bulan_ini'),
        );

        $laporan = $this->laporanService->getSummary($dari, $sampai);

        $transaksi = Transaction::paid()
            ->with(['items', 'kasir'])
            ->whereBetween('paid_at', [$dari->startOfDay(), $sampai->copy()->endOfDay()])
            ->latest('paid_at')
            ->paginate(20)
            ->withQueryString();

        return view('laporan.index', compact('laporan', 'transaksi', 'dari', 'sampai'));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'dari' => ['nullable', 'date', 'before_or_equal:sampai'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'preset' => ['nullable', 'in:hari_ini,minggu_ini,bulan_ini,bulan_lalu,tahun_ini'],
        ]);

        [$dari, $sampai] = $this->laporanService->parsePeriode(
            dari: $request->dari,
            sampai: $request->sampai,
            preset: $request->input('preset', 'bulan_ini'),
        );

        $laporan = $this->laporanService->getSummary($dari, $sampai);
        $warung = Auth::user()->warung;

        $pdf = Pdf::loadView('laporan.pdf', compact('laporan', 'warung'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
            ]);

        $namaFile = 'Laporan-' . $dari->format('dmY') . '-' . $sampai->format('dmY') . '.pdf';

        return $pdf->download($namaFile);
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'dari' => ['nullable', 'date', 'before_or_equal:sampai'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'preset' => ['nullable', 'in:hari_ini,minggu_ini,bulan_ini,bulan_lalu,tahun_ini'],
        ]);

        [$dari, $sampai] = $this->laporanService->parsePeriode(
            dari: $request->dari,
            sampai: $request->sampai,
            preset: $request->input('preset', 'bulan_ini'),
        );

        $namaFile = 'Laporan-' . $dari->format('dmY') . '-' . $sampai->format('dmY') . '.xlsx';

        return Excel::download(
            new LaporanExport($dari, $sampai, $this->laporanService),
            $namaFile
        );
    }

    public function komisi(Request $request): View
    {
        $request->validate([
            'dari' => ['nullable', 'date', 'before_or_equal:sampai'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'preset' => ['nullable', 'in:hari_ini,minggu_ini,bulan_ini,bulan_lalu,tahun_ini'],
        ]);

        [$dari, $sampai] = $this->laporanService->parsePeriode(
            dari: $request->dari,
            sampai: $request->sampai,
            preset: $request->input('preset', 'bulan_ini'),
        );

        $komisi = CommissionLedger::query()
            ->with('transaction')
            ->whereBetween('settled_at', [$dari->startOfDay(), $sampai->copy()->endOfDay()])
            ->latest('settled_at')
            ->paginate(20)
            ->withQueryString();

        $totalKomisi = CommissionLedger::query()
            ->whereBetween('settled_at', [$dari->startOfDay(), $sampai->copy()->endOfDay()])
            ->sum('commission_amount');

        return view('laporan.komisi', compact('komisi', 'totalKomisi', 'dari', 'sampai'));
    }
}
