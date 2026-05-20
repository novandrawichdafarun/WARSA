<?php

namespace App\Exports;

use App\Services\LaporanService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanExport implements WithMultipleSheets
{
  public function __construct(protected Carbon $dari, protected Carbon $sampai, protected LaporanService $laporanService, )
  {
  }

  public function sheets(): array
  {
    $laporan = $this->laporanService->getSummary($this->dari, $this->sampai);

    return [
      new LaporanSummarySheet($laporan, $this->dari, $this->sampai),
      new LaporanTransaksiSheet($this->dari, $this->sampai),
      new LaporanProdukSheet($laporan),
    ];
  }
}