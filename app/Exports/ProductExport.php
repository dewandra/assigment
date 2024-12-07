<?php

namespace App\Exports;

use App\Models\AppModelsProduct;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromCollection, WithHeadings, WithStyles
{

    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->products->map(function ($product, $index) {
            return [
                $index + 1,
                $product->name,
                $product->category->name,
                'Rp ' . number_format($product->hb, 0, ',', '.'), // Harga Beli
                'Rp ' . number_format($product->hj, 0, ',', '.'), // Harga Jual
                $product->stok,
            ];
        });
    }

    /**
     * Menambahkan header pada file Excel
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Name',
            'Kategori Produk',
            'Harga Beli',
            'Harga Jual',
            'Stok',
        ];
    }
    /**
     * Menambahkan styling untuk heading
     *
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
        // Mengubah warna latar belakang heading menjadi biru dan warna teks menjadi putih
        $sheet->getStyle('A1:F1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'dc3545'],
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
        ]);
    }
}
