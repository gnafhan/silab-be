<?php

namespace App\Imports;

use App\Models\Inventory;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InventoryImport implements ToModel, WithHeadingRow, WithValidation, WithMultipleSheets
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
        Log::info('Processing inventory row:', $row);

        try {
            $inventory = new Inventory([
                'item_name' => $row['nama_item'],
                'no_item' => $row['no_item'],
                'condition' => $this->mapKondisi($row['kondisi']),
                'alat/bhp' => $this->mapAlatBhp($row['alat_bhp']),
                'no_inv_ugm' => $row['no_inv_ugm'],
                'information' => $row['keterangan'] ?? null,
                'room_id' => (int)$row['room_id'],
                'labolatory_id' => (int)$row['laboratory_id'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            Log::info('Created inventory:', $inventory->toArray());
            return $inventory;
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = collect($failures)->map(function ($failure) {
                return "Row {$failure->row()}: {$failure->errors()[0]}";
            })->join(', ');
            Log::error('Import failed: ' . $errors);

            return back()->withErrors('error', "Import failed: {$errors}");
        } catch (\Exception $e) {
            Log::error('Import failed: ' . $e->getMessage());
            return back()->withErrors('error', 'Import failed: ' . $e->getMessage());
        }
    }

    private function mapKondisi($kondisi)
    {
        return strtolower(trim($kondisi)) === 'baik' ? 'good' : 'bad';
    }

    private function mapAlatBhp($alatBhp)
    {
        return trim($alatBhp);
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
            '*.nama_item' => 'required',
            '*.no_item' => 'required',
            '*.kondisi' => 'required|in:Baik,Rusak',
            '*.alat_bhp' => 'required|in:Alat,BHP',
            '*.no_inv_ugm' => 'required',
            '*.room_id' => 'numeric|exists:rooms,id',
            '*.laboratory_id' => 'numeric|exists:labolatories,id',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nama_item.required' => 'Namax item is required',
            '*.no_item.required' => 'No item is required',
            '*.kondisi.required' => 'Kondisi is required',
            '*.kondisi.in' => 'Kondisi must be either Baik or Rusak',
            '*.alat_bhp.required' => 'Alat/BHP is required',
            '*.alat_bhp.in' => 'Alat/BHP must be either Alat or BHP',
            '*.no_inv_ugm.required' => 'No inventaris UGM is required',
            '*.room_id.required' => 'Room ID is required',
            '*.room_id.exists' => 'Room ID is invalid',
            '*.laboratory_id.required' => 'Laboratory ID is required',
            '*.laboratory_id.exists' => 'Laboratory ID is invalid',
        ];
    }
}