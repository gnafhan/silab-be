<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GeneratePengadaanExcel extends Command
{
    protected $signature = 'excel:generate-pengadaan';
    protected $description = 'Generate pengadaan Excel template';

    public function handle()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Worksheet'); // Set first sheet name

        // Set headers
        $headers = [
            'nama_item',
            'spesifikasi',
            'jumlah',
            'harga_item',
            'bulan_pengadaan',
            'laboratory_id'
        ];

        foreach (range('A', 'F') as $key => $column) {
            $sheet->setCellValue($column . '1', $headers[$key]);
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Add example row
        $exampleData = [
            'Laptop Dell',
            'Intel i5, 8GB RAM',
            '2',
            '15000000',
            '2024-01-15',
            '1'  // laboratory_id example
        ];

        foreach (range('A', 'F') as $key => $column) {
            $sheet->setCellValue($column . '2', $exampleData[$key]);
        }

        // Format date column
        $sheet->getStyle('E2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD2);

        // Add instructions sheet
        $instructionSheet = $spreadsheet->createSheet();
        $instructionSheet->setTitle('Instructions');
        $instructionSheet->setCellValue('A1', 'Instructions for filling the template:');
        $instructionSheet->setCellValue('A2', '1. nama_item: Required, nama item pengadaan');
        $instructionSheet->setCellValue('A3', '2. spesifikasi: Required, spesifikasi item');
        $instructionSheet->setCellValue('A4', '3. jumlah: Required, jumlah item (numeric)');
        $instructionSheet->setCellValue('A5', '4. harga_item: Required, harga per item (numeric)');
        $instructionSheet->setCellValue('A6', '5. bulan_pengadaan: Required, tanggal pengadaan (format: YYYY-MM-DD)');
        $instructionSheet->setCellValue('A7', '6. laboratory_id: Required, ID laboratorium');

        // Save file
        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/templates');
        
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        
        $writer->save($path . '/pengadaan_template.xlsx');

        $this->info('Pengadaan Excel template has been generated successfully!');
    }
}
