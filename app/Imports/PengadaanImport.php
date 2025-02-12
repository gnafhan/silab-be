<?php

namespace App\Imports;

use App\Models\Pengadaan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PengadaanImport implements ToModel, WithHeadingRow, WithValidation, WithMultipleSheets
{
    use WithConditionalSheets;

    public function sheets(): array
    {
        return [
            0 => $this, // Only process first sheet
        ];
    }

    public function conditionalSheets(): array
    {
        return [
            'Worksheet' => $this, // Only process sheet named 'Worksheet'
            0 => $this, // Also process first sheet if 'Worksheet' doesn't exist
        ];
    }

    public function model(array $row)
    {
        Log::info('Processing pengadaan row:', $row);
        
        try {
            $bulanPengadaan = null;
            if (isset($row['bulan_pengadaan'])) {
                if (is_numeric($row['bulan_pengadaan'])) {
                    $bulanPengadaan = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['bulan_pengadaan']);
                } else {
                    $bulanPengadaan = Carbon::parse($row['bulan_pengadaan']);
                }
            }

            $data = [
                'item_name' => $row['nama_item'] ?? null,
                'spesifikasi' => $row['spesifikasi'] ?? null,
                'jumlah' => isset($row['jumlah']) ? (int)$row['jumlah'] : null,
                'harga_item' => isset($row['harga_item']) ? (int)$row['harga_item'] : null,
                'bulan_pengadaan' => $bulanPengadaan ?? now(),
                'labolatory_id' => isset($row['laboratory_id']) ? (int)$row['laboratory_id'] : null,
            ];

            Log::info('Creating pengadaan with data:', $data);

            return new Pengadaan($data);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            Log::error('Error processing row: ' . $e->getMessage());
            $failures = $e->failures();
     
            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
            throw $e;
        }
    }

    public function prepareForValidation($data, $index)
    {
        foreach ($data as $key => $value) {
            $data[$key] = is_string($value) ? trim($value) : $value;
        }
        return $data;
    }

    public function rules(): array
    {
        return [
            'nama_item' => 'required|string',
            'spesifikasi' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
            'harga_item' => 'required|numeric|min:0',
            'laboratory_id' => 'required|exists:labolatories,id',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_item.required' => 'Namas item is required',
            'spesifikasi.required' => 'Spesifikasi is required',
            'jumlah.required' => 'Jumlah is required',
            'jumlah.numeric' => 'Jumlah must be a number',
            'jumlah.min' => 'Jumlah must be at least 1',
            'harga_item.required' => 'Harga item is required',
            'harga_item.numeric' => 'Harga item must be a number',
            'harga_item.min' => 'Harga item must be at least 0',
            'laboratory_id.required' => 'Laboratory ID is required',
            'laboratory_id.exists' => 'Laboratory ID is invalid',
        ];
    }
}