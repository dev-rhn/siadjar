<?php

namespace App\Reports;

use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReportRegistry
{
    /**
     * @return array<string, class-string<BaseReport>>
     */
    public static function classes(): array
    {
        $classes = [];

        foreach (config('laporan.reports', []) as $class) {
            if (! is_subclass_of($class, BaseReport::class)) {
                throw new InvalidArgumentException("{$class} harus turunan dari ".BaseReport::class);
            }

            $classes[$class::slug()] = $class;
        }

        return $classes;
    }

    /**
     * @return array<string, string> slug => judul
     */
    public static function options(): array
    {
        return collect(static::classes())
            ->map(fn (string $class) => app($class)->title())
            ->all();
    }

    public static function find(?string $slug): ?BaseReport
    {
        $class = static::classes()[$slug] ?? null;

        return $class ? app($class) : null;
    }

    public static function findOrFail(?string $slug): BaseReport
    {
        return static::find($slug) ?? throw new NotFoundHttpException('Laporan tidak ditemukan.');
    }
}
