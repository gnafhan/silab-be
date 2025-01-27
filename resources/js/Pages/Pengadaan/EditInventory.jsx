
import { useState, useEffect } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function EditInventory({ auth, pengadaan, availableInventories }) {
    const [selectedInventories, setSelectedInventories] = useState(
        pengadaan.item_pengadaans.map(item => item.inventory_id)
    );

    const { data, setData, post, processing, errors } = useForm({
        inventory_ids: selectedInventories,
    });

    const handleCheckboxChange = (inventoryId) => {
        const newSelected = selectedInventories.includes(inventoryId)
            ? selectedInventories.filter(id => id !== inventoryId)
            : [...selectedInventories, inventoryId];
        
        setSelectedInventories(newSelected);
        setData('inventory_ids', newSelected);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('pengadaan.update-inventory', pengadaan.id));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Manage Inventories</h2>}
        >
            <Head title="Manage Inventories" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <form onSubmit={handleSubmit}>
                                <div className="space-y-4">
                                    {availableInventories.map((inventory) => (
                                        <div key={inventory.id} className="flex items-center">
                                            <input
                                                type="checkbox"
                                                id={`inventory-${inventory.id}`}
                                                checked={selectedInventories.includes(inventory.id)}
                                                onChange={() => handleCheckboxChange(inventory.id)}
                                                className="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            />
                                            <label htmlFor={`inventory-${inventory.id}`} className="ml-2">
                                                {inventory.item_name} - {inventory.no_item}
                                            </label>
                                        </div>
                                    ))}
                                </div>

                                {errors.inventory_ids && <div className="text-red-500">{errors.inventory_ids}</div>}

                                <div className="mt-6">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                    >
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}