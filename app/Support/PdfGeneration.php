<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\PdfFormat;
use App\Enums\PdfGeneratorColumnPosition;
use App\Enums\PdfGeneratorColumnType;
use App\Enums\PdfOrientation;
use Illuminate\Support\Collection;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Orientation;

use function in_array;

final readonly class PdfGeneration
{
    /**
     * The columns to be used for PDF generation.
     *
     * @var Collection<string,array{label:string,position:string,type:string}>
     */
    private Collection $columnsCollection;

    /**
     * Create a new PdfGenerationColumns instance.
     *
     * @param  array<string,array{label?:string,position?:PdfGeneratorColumnPosition,type?:PdfGeneratorColumnType}>|Collection<string,array{label?:string,position?:PdfGeneratorColumnPosition,type?:PdfGeneratorColumnType}>  $columns
     */
    public function __construct(array|Collection $columns = [])
    {
        if (! $columns instanceof Collection) {
            $columns = collect($columns);
        }

        $this->columnsCollection = $columns->map(function (array $column, string $name): array {
            $column['position'] = isset($column['position']) ? $column['position']->value : PdfGeneratorColumnPosition::LEFT->value;
            $column['type'] = isset($column['type']) ? $column['type']->value : PdfGeneratorColumnType::TEXT->value;

            if (! isset($column['label'])) {
                $column['label'] = $name;
            }

            return $column;
        });
    }

    /**
     * Get the columns for the view.
     *
     * @return array<int,array{name:string,label:string,selected:bool}>
     */
    public function getForView(): array
    {
        /**
         * @var array<int,array{name:string,label:string,selected:bool}> $data
         */
        $data = $this->columnsCollection
            ->map(fn (array $col, string $name): array => ['name' => $name, 'label' => $col['label'], 'selected' => true])
            ->values()
            ->toArray();

        return $data;
    }

    /**
     * Get the columns for the PDF generation.
     *
     * @param  array<string>  $unSelectedColumns
     * @return array<string,array{label:string,position:string,type:string}>
     */
    public function getForPdf(array $unSelectedColumns = []): array
    {
        /**
         * @var array<string,array{label:string,position:string,type:string}> $data
         */
        $data = $this->columnsCollection
            ->filter(fn (array $_, string $name): bool => ! in_array($name, $unSelectedColumns))
            ->toArray();

        return $data;
    }

    /**
     * Get the format options as an array.
     *
     * @return array{label:string,value:string}[]
     */
    public function getFormatOptions(): array
    {
        return SelectOption::createFromEnum(PdfFormat::class);
    }

    /**
     * Get the orientation options as an array.
     *
     * @return array{label:string,value:string}[]
     */
    public function getOrientationOptions(): array
    {
        return SelectOption::createFromEnum(PdfOrientation::class);
    }
}
