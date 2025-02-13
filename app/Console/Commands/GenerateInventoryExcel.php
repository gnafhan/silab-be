<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GenerateInventoryExcel extends Command
{
    protected $signature = 'excel:generate-inventory';
    protected $description = 'Generate inventory Excel template';

    public function handle()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Worksheet'); // Set first sheet name

        // Set headers
        $headers = [
            'nama_item', 
            'no_item', 
            'kondisi', 
            'alat_bhp',
            'no_inv_ugm',
            'keterangan',
            'room_id',
            'laboratory_id'
        ];

        foreach (range('A', 'H') as $key => $column) {
            $sheet->setCellValue($column . '1', $headers[$key]);
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Add example row
        $exampleData = [
            'Laptop Dell',
            'LPT-001',
            'Baik',
            'Alat',
            'UGM-001',
            'Laptop untuk praktikum',
            '1', // room_id example
            '1'  // laboratory_id example
        ];

        foreach (range('A', 'H') as $key => $column) {
            $sheet->setCellValue($column . '2', $exampleData[$key]);
        }

        // Add instructions sheet
        $instructionSheet = $spreadsheet->createSheet();
        $instructionSheet->setTitle('Instructions');
        $instructionSheet->setCellValue('A1', 'Instructions for filling the template:');
        $instructionSheet->setCellValue('A2', '1. nama_item: Required, nama item inventory');
        $instructionSheet->setCellValue('A3', '2. no_item: Required, nomor item');
        $instructionSheet->setCellValue('A4', '3. kondisi: Required, kondisi item (Baik/Rusak)');
        $instructionSheet->setCellValue('A5', '4. alat_bhp: Required, tipe item (Alat/BHP)');
        $instructionSheet->setCellValue('A6', '5. no_inv_ugm: Required, nomor inventaris UGM');
        $instructionSheet->setCellValue('A7', '6. keterangan: Optional, keterangan tambahan');
        $instructionSheet->setCellValue('A8', '7. room_id: Required, ID ruangan');
        $instructionSheet->setCellValue('A9', '8. laboratory_id: Required, ID laboratorium');

        // Save file
        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/templates');
        
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        
        $writer->save($path . '/inventory_template.xlsx');

        $this->info('Inventory Excel template has been generated successfully!');
    }
}