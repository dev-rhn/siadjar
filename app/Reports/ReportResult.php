<?php

namespace App\Reports;

class ReportResult
{
    /**
     * @param  array<string, mixed>  $filters
     * @param  array<string, string>  $filterDescriptions
     * @param  array<string, array{label: string, width?: string, align?: string}>  $columns
     * @param  array<int, array<string, string|int|null>>  $rows
     * @param  array<string, array<string, string|int>>  $summary
     * @param  array<int, array{title: string, columns: array<string, array{label: string, width?: string, align?: string}>, rows: array<int, array<string, string|int|null>>}>  $tables
     */
    public function __construct(
        public readonly BaseReport $report,
        public readonly array $filters,
        public readonly array $filterDescriptions,
        public readonly array $columns,
        public readonly array $rows,
        public readonly array $summary,
        public readonly array $tables = [],
    ) {}

    public function rowCount(): int
    {
        return count($this->rows);
    }

    public function filterDescriptionText(): string
    {
        if ($this->filterDescriptions === []) {
            return 'Semua data';
        }

        return collect($this->filterDescriptions)
            ->map(fn (string $value, string $label) => $label.': '.$value)
            ->implode('; ');
    }
}
