<?php

namespace App\Reports;

use App\Models\LaporanArsip;

class GeneratedReport
{
    public function __construct(
        public readonly string $content,
        public readonly string $fileName,
        public readonly ?LaporanArsip $arsip,
    ) {}
}
