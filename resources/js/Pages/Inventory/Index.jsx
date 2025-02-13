import { useState, useEffect } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router, usePage } from '@inertiajs/react';
import debounce from 'lodash/debounce';
import { Edit, Trash2, ChevronLeft, ChevronRight } from 'lucide-react';

export default function Index({ auth, inventories, laboratories, filters }) {
    const [search, setSearch] = useState(filters.search || '');
    const [limit, setLimit] = useState(filters.limit || '10');
    const [labFilter, setLabFilter] = useState(filters.laboratory || '');
    const [importing, setImporting] = useState(false);

    const props = usePage().props;
    console.log(props);

    // Debounced search function
    const debouncedSearch = debounce((query) => {
        router.get(
            route('inventory.index'),
            { search: query, limit, laboratory: labFilter },
            { preserveState: true }
        );
    }, 300);

    useEffect(() => {
        debouncedSearch(search);
        return () => debouncedSearch.cancel();
    }, [search]);

    const handleLimitChange = (e) => {
        const newLimit = e.target.value;
        setLimit(newLimit);
        router.get(
            route('inventory.index'),
            { search, limit: newLimit, laboratory: labFilter },
            { preserveState: true }
        );
    };

    const handleLabFilterChange = (e) => {
        const newLabFilter = e.target.value;
        setLabFilter(newLabFilter);
        router.get(
            route('inventory.index'),
            { search, limit, laboratory: newLabFilter },
            { preserveState: true }
        );
    };

    const handleDelete = (id) => {
        if (confirm('Are you sure you want to delete this item?')) {
            router.delete(route('inventory.destroy', id));
        }
    };

    const handleImport = (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);

        setImporting(true);
        router.post(route('inventory.import'), formData, {
            onSuccess: (r) => {
                setImporting(false);
                e.target.value = null;
            },
            onError: (errors) => {
                console.log(errors)
                setImporting(false);
                e.target.value = null;
            },
        });
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Manajemen Inventaris</h2>}
        >
            <Head title="Inventory" />

            <div className="py-12">
                <div className="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            {/* Add Import Controls */}
                            <div className="mb-4 flex items-center space-x-4">
                                <a
                                    href={route('inventory.template')}
                                    className="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                    download
                                >
                                    Download Template 
                                </a>
                                <div>
                                    <input
                                        type="file"
                                        accept=".xlsx,.xls"
                                        onChange={handleImport}
                                        className="hidden"
                                        id="importFile"
                                        disabled={importing}
                                    />
                                    <label
                                        htmlFor="importFile"
                                        className={`cursor-pointer bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded ${
                                            importing ? 'opacity-50 cursor-not-allowed' : ''
                                        }`}
                                    >
                                        {importing ? 'Importing...' : 'Import Data'}
                                    </label>
                                </div>
                            </div>

                            {/* Search and Filter Controls */}
                            <div className="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                                <div className="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
                                    <input
                                        type="text"
                                        placeholder="Search by name or no item..."
                                        className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        value={search}
                                        onChange={(e) => setSearch(e.target.value)}
                                    />

                                    <select
                                        value={labFilter}
                                        onChange={handleLabFilterChange}
                                        className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option value="">Semua Labolatorium</option>
                                        {laboratories.map((lab) => (
                                            <option key={lab.id} value={lab.id}>{lab.name}</option>
                                        ))}
                                    </select>

                                    <select
                                        value={limit}
                                        onChange={handleLimitChange}
                                        className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option value="10">10 per halaman</option>
                                        <option value="25">25 per halaman</option>
                                        <option value="50">50 per halaman</option>
                                        <option value="100">100 per halaman</option>
                                    </select>
                                </div>

                                <Link
                                    href={route('inventory.create')}
                                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Add New Item
                                </Link>
                            </div>

                            {/* Table */}
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Barang</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ruangan</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Laboratorium</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {inventories.data.map((inventory) => (
                                            <tr key={inventory.id}>
                                                <td className="px-6 py-4 whitespace-nowrap">{inventory.item_name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">{inventory.no_item}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">{inventory.condition}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">{inventory.room?.name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">{inventory.laboratory?.name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                    <Link
                                                        href={route('inventory.edit', inventory.id)}
                                                        className="inline-flex items-center px-3 py-2 bg-indigo-500 hover:bg-indigo-700 text-white rounded-md space-x-2"
                                                    >
                                                        <Edit className="w-4 h-4" />
                                                        <span>Ubah</span>
                                                    </Link>
                                                    <button
                                                        onClick={() => handleDelete(inventory.id)}
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

                            {/* Pagination */}
                            {inventories.links && (
                                <div className="mt-6">
                                    <div className="flex items-center justify-between">
                                        <div className="text-sm text-gray-700">
                                            Menampilkan {inventories.from} sampai {inventories.to} dari {inventories.total} hasil
                                        </div>
                                        <div className="flex items-center space-x-2">
                                            <button
                                                onClick={() => inventories.prev_page_url && router.get(inventories.prev_page_url)}
                                                className={`inline-flex items-center px-3 py-2 rounded-md ${
                                                    !inventories.prev_page_url
                                                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                        : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                                                }`}
                                                disabled={!inventories.prev_page_url}
                                            >
                                                <ChevronLeft className="w-4 h-4 mr-1" />
                                                <span>Sebelumnya</span>
                                            </button>

                                            {/* Numbered Pages */}
                                            <div className="flex space-x-1">
                                                {inventories.links.slice(1, -1).map((link, i) => (
                                                    <button
                                                        key={i}
                                                        onClick={() => link.url && router.get(link.url)}
                                                        className={`px-3 py-2 rounded-md ${
                                                            link.active
                                                                ? 'bg-blue-500 text-white'
                                                                : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                                                        } ${!link.url && 'opacity-50 cursor-not-allowed'}`}
                                                        disabled={!link.url}
                                                    >
                                                        {link.label}
                                                    </button>
                                                ))}
                                            </div>

                                            <button
                                                onClick={() => inventories.next_page_url && router.get(inventories.next_page_url)}
                                                className={`inline-flex items-center px-3 py-2 rounded-md ${
                                                    !inventories.next_page_url
                                                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                        : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                                                }`}
                                                disabled={!inventories.next_page_url}
                                            >
                                                <span>Berikutnya</span>
                                                <ChevronRight className="w-4 h-4 ml-1" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}