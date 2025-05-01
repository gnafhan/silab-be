<?php

namespace App\Imports;

use App\Models\Inventory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            // Check if an active inventory with the same no_item already exists
            $exists = Inventory::where('no_item', $row['no_item'])
                ->whereNull('deleted_at')
                ->exists();
                
            if ($exists) {
                // Skip this row or throw a validation exception
                Log::warning("Skipping row with duplicate no_item: {$row['no_item']}");
                throw new \Maatwebsite\Excel\Validators\ValidationException(
                    \Illuminate\Validation\ValidationException::withMessages([
                        'no_item' => ["Nomor barang '{$row['no_item']}' sudah digunakan. Barang tidak akan diimpor."]
                    ]),
                    []
                );
            }
            
            // Check if there's a soft-deleted inventory with the same no_item
            $existingTrashed = Inventory::onlyTrashed()
                ->where('no_item', $row['no_item'])
                ->first();
                
            if ($existingTrashed) {
                try {
                    // Use our new method to safely delete the inventory with all its relations
                    $existingTrashed->forceDeleteWithRelated();
                    
                    Log::info("Force deleted soft-deleted inventory ID {$existingTrashed->id} with no_item: {$row['no_item']}");
                } catch (\Exception $e) {
                    Log::error("Error force deleting inventory: " . $e->getMessage());
                    // Continue with the creation process, but with a new no_item
                    // Append a timestamp to make it unique
                    $row['no_item'] = $row['no_item'] . '-' . time();
                    Log::info("Modified no_item to: {$row['no_item']}");
                }
            }
            
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
            // Re-throw the validation exception to be handled by the importer
            throw $e;
        } catch (\Exception $e) {
            Log::error('Import failed: ' . $e->getMessage());
            throw new \Exception('Import failed: ' . $e->getMessage());
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
            '*.no_item' => [
                'required',
                function ($attribute, $value, $fail) {
                    // The uniqueness check is already done in the model method
                    // This is just an extra validation for the no_item format if needed
                    if (empty($value)) {
                        $fail('Nomor barang tidak boleh kosong.');
                    }
                }
            ],
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