import { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';
import { Edit, Trash2 } from 'lucide-react';
import debounce from 'lodash/debounce';

export default function Index({ auth, rooms, filters }) {
    const [search, setSearch] = useState(filters.search || '');
    const [limit, setLimit] = useState(filters.limit || '10');

    const debouncedSearch = debounce((query) => {
        router.get(
            route('room.index'),
            { search: query, limit },
            { preserveState: true }
        );
    }, 300);

    const handleDelete = (id) => {
        if (confirm('Are you sure you want to delete this room?')) {
            router.delete(route('room.destroy', id));
        }
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Manajemen Ruangan</h2>}
        >
            <Head title="Rooms" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-6">
                                <div className="flex space-x-4">
                                    <input
                                        type="text"
                                        placeholder="Search rooms..."
                                        className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        value={search}
                                        onChange={(e) => {
                                            setSearch(e.target.value);
                                            debouncedSearch(e.target.value);
                                        }}
                                    />
                                    <select
                                        value={limit}
                                        onChange={(e) => {
                                            setLimit(e.target.value);
                                            router.get(
                                                route('room.index'),
                                                { search, limit: e.target.value },
                                                { preserveState: true }
                                            );
                                        }}
                                        className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option value="10">10 per page</option>
                                        <option value="25">25 per page</option>
                                        <option value="50">50 per page</option>
                                    </select>
                                </div>

                                <Link
                                    href={route('room.create')}
                                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Add New Room
                                </Link>
                            </div>

                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Ruangan</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Ruangan</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kapasitas</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {rooms.data.map((room) => (
                                            <tr key={room.id}>
                                                <td className="px-6 py-4">{room.id}</td>
                                                <td className="px-6 py-4">{room.name}</td>
                                                <td className="px-6 py-4">{room.capacity}</td>
                                                <td className="px-6 py-4 capitalize">{room.type}</td>
                                                <td className="px-6 py-4">{room.description}</td>
                                                <td className="px-6 py-4 space-x-2">
                                                    <Link
                                                        href={route('room.edit', room.id)}
                                                        className="inline-flex items-center px-3 py-2 bg-indigo-500 hover:bg-indigo-700 text-white rounded-md"
                                                    >
                                                        <Edit className="w-4 h-4 mr-2" />
                                                        Edit
                                                    </Link>
                                                    <button
                                                        onClick={() => handleDelete(room.id)}
                                                        className="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md"
                                                    >
                                                        <Trash2 className="w-4 h-4 mr-2" />
                                                        Delete
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
