import { useState, useEffect } from 'react';
import { Link, Head, router } from '@inertiajs/react';
import debounce from 'lodash/debounce';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useForceHttps } from '@/hooks/useForceHttps';

export default function Welcome({ auth, inventories, laboratories, filters, laravelVersion, phpVersion }) {
    const [search, setSearch] = useState(filters.search || '');
    const [limit, setLimit] = useState(filters.limit || '10');
    const [labFilter, setLabFilter] = useState(filters.laboratory || '');

    // Debounced search function
    const debouncedSearch = debounce((query) => {
        router.get(
            route('welcome'),
            { search: query, limit, laboratory: labFilter, page: inventories.current_page },
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
            route('welcome'),
            { search, limit: newLimit, laboratory: labFilter },
            { preserveState: true }
        );
    };

    const handleLabFilterChange = (e) => {
        const newLabFilter = e.target.value;
        setLabFilter(newLabFilter);
        router.get(
            route('welcome'),
            { search, limit, laboratory: newLabFilter },
            { preserveState: true }
        );
    };

    return (
        <>
            <Head title="Welcome" />
            <div className="min-h-screen bg-gray-100">
                <nav className="bg-white border-b border-gray-100">
                    <div className="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between h-16">
                            <div className="flex">
                                <div className="flex items-center">
                                    <div>
                                        <h1 className="text-xl font-semibold text-gray-800">
                                            Sistem Informasi Inventaris Laboratorium
                                        </h1>
                                        <p className="text-sm text-gray-600">
                                            Departemen Teknik Elektro dan Informatika
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div className="flex items-center">
                                {auth.user ? (
                                    <Link
                                        href={route('dashboard')}
                                        className="text-gray-500 hover:text-gray-700 font-semibold"
                                    >
                                        Dashboard
                                    </Link>
                                ) : (
                                    <Link
                                        href={route('login')}
                                        className="text-gray-500 hover:text-gray-700 font-semibold"
                                    >
                                        Log in
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>
                </nav>

                {/* Rest of the existing content */}
                <div className="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 py-12">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
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
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {inventories.data.map((inventory) => (
                                            <tr key={inventory.id}>
                                                <td className="px-6 py-4">
                                                    <div className="flex items-center space-x-4">
                                                        <img 
                                                            src={inventory.galleries?.[0]?.filepath 
                                                                ? `/storage/${inventory.galleries[0].filepath}`
                                                                : '/placeholder-image.jpg'} 
                                                            alt={inventory.item_name}
                                                            className="w-16 h-16 object-cover rounded"
                                                        />
                                                        <span>{inventory.item_name}</span>
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">{inventory.no_item}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">{inventory.condition}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">{inventory.room?.name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">{inventory.laboratory?.name}</td>
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
                                                onClick={() => {
                                                    if (inventories.prev_page_url) {
                                                        router.get(useForceHttps(inventories.prev_page_url));
                                                    }
                                                }}
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
                                                        onClick={() => {
                                                            if (link.url) {
                                                                router.get(useForceHttps(link.url));
                                                            }
                                                        }}
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
                                                onClick={() => {
                                                    if (inventories.next_page_url) {
                                                        router.get(useForceHttps(inventories.next_page_url));
                                                    }
                                                }}
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

                {/* <div className="text-center text-sm text-gray-500 py-4">
                    Laravel v{laravelVersion} (PHP v{phpVersion})
                </div> */}
            </div>
        </>
    );
}
