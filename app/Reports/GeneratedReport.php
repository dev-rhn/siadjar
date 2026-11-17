<?php

namespace App\Reports;

use App\Models\LaporanArsip;

class GeneratedReport
{
    /**
     * @param  string|null  $kunciAkses  kunci link preview (plain); hanya ada saat PDF dibuat dengan link
     */
    public function __construct(
        public readonly string $content,
        public readonly string $fileName,
        public readonly ?LaporanArsip $arsip,
        public readonly ?string $kunciAkses = null,
    ) {}
}
