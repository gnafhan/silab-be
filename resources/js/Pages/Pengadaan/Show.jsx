import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';
import { Trash2 } from 'lucide-react';

export default function Show({ auth, pengadaan }) {
    const getMonthName = (date) => {
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return months[new Date(date).getMonth()];
    };

    const handleRemoveInventory = (inventoryId) => {
        if (confirm('Are you sure you want to remove this inventory?')) {
            router.delete(route('pengadaan.remove-inventory', [pengadaan.id, inventoryId]));
        }
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Detail Pengadaan</h2>}
        >
            <Head title="Detail Pengadaan" />

            <div className="py-12">
                <div className="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="mb-6">
                                <h3 className="text-lg font-semibold mb-4">Informasi Pengadaan</h3>
                                <div className="grid grid-cols-2 gap-4">
                                    <div>
                                        <p><strong>Nama Barang:</strong> {pengadaan.item_name}</p>
                                        <p><strong>Spesifikasi:</strong> {pengadaan.spesifikasi}</p>
                                        <p><strong>Jumlah:</strong> {pengadaan.jumlah}</p>
                                    </div>
                                    <div>
                                        <p><strong>Harga Barang:</strong> {pengadaan.harga_item}</p>
                                        <p><strong>Bulan:</strong> {getMonthName(pengadaan.bulan_pengadaan)} {new Date(pengadaan.bulan_pengadaan).getFullYear()}</p>
                                        <p><strong>Laboratorium:</strong> {pengadaan.laboratory?.name}</p>
                                    </div>
                                </div>
                            </div>

                            <div className="mt-8">
                                <div className="flex justify-between items-center mb-4">
                                    <h3 className="text-lg font-semibold">Inventories</h3>
                                    <Link
                                        href={route('pengadaan.edit-inventory', pengadaan.id)}
                                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                    >
                                        Manage Inventories
                                    </Link>
                                </div>

                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Barang</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {pengadaan.item_pengadaans.map((item) => (
                                            <tr key={item.id}>
                                                <td className="px-6 py-4">{item.inventory.item_name}</td>
                                                <td className="px-6 py-4">{item.inventory.no_item}</td>
                                                <td className="px-6 py-4">{item.inventory.condition}</td>
                                                <td className="px-6 py-4">{item.inventory.information}</td>
                                                <td className="px-6 py-4">
                                                    <button
                                                        onClick={() => handleRemoveInventory(item.inventory.id)}
                                                        className="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md space-x-2"
                                                    >
                                                        <Trash2 className="w-4 h-4" />
                                                        <span>Hapus</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}