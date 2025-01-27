import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Create({ auth, laboratories }) {
    const { data, setData, post, processing, errors } = useForm({
        item_name: '',
        spesifikasi: '',
        jumlah: '',
        harga_item: '',
        bulan_pengadaan: '',
        labolatory_id: ''
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('pengadaan.store'));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Create Pengadaan</h2>}
        >
            <Head title="Create Pengadaan" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form onSubmit={handleSubmit} className="p-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Item Name</label>
                                    <input
                                        type="text"
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        value={data.item_name}
                                        onChange={e => setData('item_name', e.target.value)}
                                    />
                                    {errors.item_name && <div className="text-red-500 text-sm mt-1">{errors.item_name}</div>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Spesifikasi</label>
                                    <textarea
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        value={data.spesifikasi}
                                        onChange={e => setData('spesifikasi', e.target.value)}
                                    />
                                    {errors.spesifikasi && <div className="text-red-500 text-sm mt-1">{errors.spesifikasi}</div>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Jumlah</label>
                                    <input
                                        type="number"
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        value={data.jumlah}
                                        onChange={e => setData('jumlah', e.target.value)}
                                    />
                                    {errors.jumlah && <div className="text-red-500 text-sm mt-1">{errors.jumlah}</div>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Harga Item</label>
                                    <input
                                        type="number"
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        value={data.harga_item}
                                        onChange={e => setData('harga_item', e.target.value)}
                                    />
                                    {errors.harga_item && <div className="text-red-500 text-sm mt-1">{errors.harga_item}</div>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Bulan Pengadaan</label>
                                    <input
                                        type="date"
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        value={data.bulan_pengadaan}
                                        onChange={e => setData('bulan_pengadaan', e.target.value)}
                                    />
                                    {errors.bulan_pengadaan && <div className="text-red-500 text-sm mt-1">{errors.bulan_pengadaan}</div>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Laboratory</label>
                                    <select
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        value={data.labolatory_id}
                                        onChange={e => setData('labolatory_id', e.target.value)}
                                    >
                                        <option value="">Select Laboratory</option>
                                        {laboratories.map(lab => (
                                            <option key={lab.id} value={lab.id}>{lab.name}</option>
                                        ))}
                                    </select>
                                    {errors.labolatory_id && <div className="text-red-500 text-sm mt-1">{errors.labolatory_id}</div>}
                                </div>
                            </div>

                            <div className="mt-6 flex justify-end">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                >
                                    Create Pengadaan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
