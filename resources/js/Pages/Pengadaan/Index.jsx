import { useState, useEffect } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';
import debounce from 'lodash/debounce';
import { Eye, Edit, Trash2, ChevronLeft, ChevronRight } from 'lucide-react';
import { useForceHttps } from '@/hooks/useForceHttps';

export default function Index({ auth, pengadaans, laboratories, filters }) {
    const [search, setSearch] = useState(filters.search || '');
    const [limit, setLimit] = useState(filters.limit || '10');
    const [labFilter, setLabFilter] = useState(filters.laboratory || '');
    const [monthFilter, setMonthFilter] = useState(filters.month || '');
    const [yearFilter, setYearFilter] = useState(filters.year || '');
    const [page, setPage] = useState(filters.page || '1');
    const [importing, setImporting] = useState(false);

    const handleSearch = (e) => {
        e.preventDefault();
        router.get(
            route('pengadaan.index'),
            { search, limit, laboratory: labFilter, month: monthFilter, year: yearFilter },
            { preserveState: true }
        );
    };

    const handleLimitChange = (e) => {
        const newLimit = e.target.value;
        setLimit(newLimit);
        router.get(
            route('pengadaan.index'),
            { search, limit: newLimit, laboratory: labFilter, month: monthFilter, year: yearFilter, page },
            { preserveState: true }
        );
    };

    const handleLabFilterChange = (e) => {
        const newLabFilter = e.target.value;
        setLabFilter(newLabFilter);
        router.get(
            route('pengadaan.index'),
            { search, limit, laboratory: newLabFilter, month: monthFilter, year: yearFilter },
            { preserveState: true }
        );
    };

    const handleMonthFilterChange = (e) => {
        const newMonthFilter = e.target.value;
        setMonthFilter(newMonthFilter);
        router.get(
            route('pengadaan.index'),
            { search, limit, laboratory: labFilter, month: newMonthFilter, year: yearFilter },
            { preserveState: true }
        );
    };

    const handleYearFilterChange = (e) => {
        const newYearFilter = e.target.value;
        setYearFilter(newYearFilter);
        router.get(
            route('pengadaan.index'),
            { search, limit, laboratory: labFilter, month: monthFilter, year: newYearFilter },
            { preserveState: true }
        );
    };

    const handleDelete = (id) => {
        if (confirm('Are you sure you want to delete this item?')) {
            router.delete(route('pengadaan.destroy', id));
        }
    };

    const handleImport = (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);

        setImporting(true);
        router.post(route('pengadaan.import'), formData, {
            onSuccess: () => {
                setImporting(false);
                e.target.value = null;
            },
            onError: () => {
                setImporting(false);
                e.target.value = null;
            },
        });
    };

    const getMonthName = (monthNumber) => {
        // console.log(monthNumber)
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return months[parseInt(monthNumber)];
    };

    // console.log(pengadaans)

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Manajemen Pengadaan</h2>}
        >
            <Head title="Pengadaan" />

            <div className="py-12">
                <div className="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            {/* Add Import Controls before existing search controls */}
                            <div className="mb-4 flex items-center space-x-4">
                                <a
                                    href={route('pengadaan.template')}
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
                                    <form onSubmit={handleSearch} className="flex space-x-2">
                                        <input
                                            type="text"
                                            placeholder="Cari barang..."
                                            className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            value={search}
                                            onChange={(e) => setSearch(e.target.value)}
                                        />
                                        <button
                                            type="submit"
                                            className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                        >
                                            Search
                                        </button>
                                    </form>

                                    <select
                                        value={labFilter}
                                        onChange={handleLabFilterChange}
                                        className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option value="">Semua Laboratorium</option>
                                        {laboratories.map((lab) => (
                                            <option key={lab.id} value={lab.id}>{lab.name}</option>
                                        ))}
                                    </select>

                                    <select
                                        value={monthFilter}
                                        onChange={handleMonthFilterChange}
                                        className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option value="">Semua Bulan</option>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>

                                    <select
                                        value={yearFilter}
                                        onChange={handleYearFilterChange}
                                        className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option value="">Semua Tahun</option>
                                        {Array.from({ length: 10 }, (_, i) => new Date().getFullYear() - i).map(year => (
                                            <option key={year} value={year}>{year}</option>
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
                                    href={route('pengadaan.create')}
                                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Add New Pengadaan
                                </Link>
                            </div>

                            {/* Table */}
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Spesifikasi</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Barang</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Laboratorium</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {pengadaans.data.map((pengadaan) => (
                                            <tr key={pengadaan.id}>
                                                <td className="px-6 py-4 whitespace-nowrap">{pengadaan.item_name}</td>
                                                <td className="px-6 py-4">{pengadaan.spesifikasi}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">{pengadaan.jumlah}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">{pengadaan.harga_item}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    {getMonthName(parseInt(new Date(`${pengadaan.bulan_pengadaan}`).getMonth()))} {new Date(`${pengadaan.bulan_pengadaan}`).getFullYear()}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">{pengadaan.laboratory?.name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                    <Link
                                                        href={route('pengadaan.show', pengadaan.id)}
                                                        className="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-md space-x-2"
                                                    >
                                                        <Eye className="w-4 h-4" />
                                                        <span>Lihat</span>
                                                    </Link>
                                                    <Link
                                                        href={route('pengadaan.edit', pengadaan.id)}
                                                        className="inline-flex items-center px-3 py-2 bg-indigo-500 hover:bg-indigo-700 text-white rounded-md space-x-2"
                                                    >
                                                        <Edit className="w-4 h-4" />
                                                        <span>Ubah</span>
                                                    </Link>
                                                    <button
                                                        onClick={() => handleDelete(pengadaan.id)}
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
                            {pengadaans.links && (
                                <div className="mt-6">
                                    <div className="flex items-center justify-between">
                                        <div className="text-sm text-gray-700">
                                            Menampilkan {pengadaans.from} sampai {pengadaans.to} dari {pengadaans.total} hasil
                                        </div>
                                        <div className="flex items-center space-x-2">
                                            <button
                                                onClick={() => pengadaans.prev_page_url && router.get(useForceHttps(pengadaans.prev_page_url))}
                                                className={`inline-flex items-center px-3 py-2 rounded-md ${
                                                    !pengadaans.prev_page_url
                                                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                        : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                                                }`}
                                                disabled={!pengadaans.prev_page_url}
                                            >
                                                <ChevronLeft className="w-4 h-4 mr-1" />
                                                <span>Previous</span>
                                            </button>

                                            {/* Numbered Pages */}
                                            <div className="flex space-x-1">
                                                {pengadaans.links.slice(1, -1).map((link, i) => (
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
                                                onClick={() => pengadaans.next_page_url && router.get(useForceHttps(pengadaans.next_page_url))}
                                                className={`inline-flex items-center px-3 py-2 rounded-md ${
                                                    !pengadaans.next_page_url
                                                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                        : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                                                }`}
                                                disabled={!pengadaans.next_page_url}
                                            >
                                                <span>Next</span>
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