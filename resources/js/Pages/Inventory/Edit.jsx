import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router, useForm } from "@inertiajs/react";
import { Trash2, X } from "lucide-react";
import { useEffect, useState } from "react";
import Select from 'react-select';

// Add this new component at the top of the file
const ImageModal = ({ isOpen, image, onClose }) => {
    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div className="relative max-w-4xl max-h-[90vh]">
                <button
                    onClick={onClose}
                    className="absolute -top-4 -right-4 bg-red-500 text-white p-2 rounded-full hover:bg-red-600"
                >
                    <X className="w-4 h-4" />
                </button>
                <img
                    src={`/storage/${image}`}
                    alt="Full size"
                    className="max-h-[90vh] rounded-lg"
                />
            </div>
        </div>
    );
};

export default function Edit({ auth, inventory, rooms, laboratories }) {
    // Add state for modal
    const [selectedImage, setSelectedImage] = useState(null);
    
    const { data, setData, put, processing, errors, post } = useForm({
        item_name: inventory.item_name,
        no_item: inventory.no_item,
        condition: inventory.condition,
        "alat/bhp": inventory["alat/bhp"],
        no_inv_ugm: inventory.no_inv_ugm,
        information: inventory.information,
        room_id: inventory.room_id,
        labolatory_id: inventory.labolatory_id,
        gallery: []
    });

    useEffect(() => {
        // If user is laboran, set and disable laboratory selection
        if (auth.user.role === 'laboran') {
            setData('labolatory_id', auth.user.lab_id);
        }
    }, []);

    const handleSubmit = (e) => {
        e.preventDefault();
        const formData = new FormData();
        
        // Append existing form data
        Object.keys(data).forEach(key => {
            if (key !== 'gallery') {
                formData.append(key, data[key]);
            }
        });
        
        // Append gallery files
        // if (data.gallery) {
        //     Array.from(data.gallery).forEach(file => {
        //         formData.append('gallery[]', file);
        //     });
        // }

        const formJSON = Object.fromEntries(formData.entries());


        router.post(route('inventory.update', inventory.id), {
            forceFormData: true,
            _method: 'PUT',
            data: {galleries: data.gallery, ...formJSON},
            galleries: data.gallery,
            ...formJSON
        });
    };

    const handleImageUpload = (e) => {
        setData('gallery', e.target.files);
    };

    const handleDeleteImage = (id) => {
        if (confirm('Are you sure you want to delete this image?')) {
            router.delete(route('inventory.gallery.delete', id));
        }
    };

    const openModal = (filepath) => {
        setSelectedImage(filepath);
    };

    const closeModal = () => {
        setSelectedImage(null);
    };

    const roomOptions = rooms.map(room => ({
        value: room.id,
        label: room.name
    }));

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Inventory Item
                </h2>
            }
        >
            <Head title="Edit Inventaris" />

            {/* Add Modal component */}
            <ImageModal
                isOpen={!!selectedImage}
                image={selectedImage}
                onClose={closeModal}
            />

            <div className="py-12">
                <div className="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form onSubmit={handleSubmit} className="p-6">
                            {/* Same form fields as Create.jsx */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">
                                        Nama Barang
                                    </label>
                                    <input
                                        type="text"
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                        value={data.item_name}
                                        onChange={(e) =>
                                            setData("item_name", e.target.value)
                                        }
                                    />
                                    {errors.item_name && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.item_name}
                                        </div>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">
                                        No Inventaris Barang
                                    </label>
                                    <input
                                        type="text"
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                        value={data.no_item}
                                        onChange={(e) =>
                                            setData("no_item", e.target.value)
                                        }
                                    />
                                    {errors.no_item && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.no_item}
                                        </div>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">
                                        Kondisi
                                    </label>
                                    <select
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                        value={data.condition}
                                        onChange={(e) =>
                                            setData("condition", e.target.value)
                                        }
                                    >
                                        <option value="">
                                            Pilih Kondisi
                                        </option>
                                        <option value="good">Baik</option>
                                        <option value="bad">Buruk</option>
                                    </select>
                                    {errors.condition && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.condition}
                                        </div>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">
                                        Alat/BHP
                                    </label>
                                    <select
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                        value={data["alat/bhp"]}
                                        onChange={(e) =>
                                            setData("alat/bhp", e.target.value)
                                        }
                                    >
                                        <option value="">Pilih Alat/BHP</option>
                                        <option value="alat">Alat</option>
                                        <option value="bhp">BHP</option>
                                    </select>
                                    {errors["alat/bhp"] && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors["alat/bhp"]}
                                        </div>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">
                                        No Inv UGM
                                    </label>
                                    <input
                                        type="text"
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                        value={data.no_inv_ugm}
                                        onChange={(e) =>
                                            setData(
                                                "no_inv_ugm",
                                                e.target.value
                                            )
                                        }
                                    />
                                    {errors.no_inv_ugm && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.no_inv_ugm}
                                        </div>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">
                                        Ruangan
                                    </label>
                                    <Select
                                        className="mt-1"
                                        options={roomOptions}
                                        value={roomOptions.find(option => option.value === data.room_id)}
                                        onChange={(option) => setData('room_id', option ? option.value : '')}
                                        isClearable
                                        placeholder="Cari ruangan..."
                                        classNames={{
                                            control: (state) => 'border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500'
                                        }}
                                    />
                                    {errors.room_id && <div className="text-red-500 text-sm mt-1">{errors.room_id}</div>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">
                                        Laboratorium
                                    </label>
                                    <select
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                        value={data.labolatory_id}
                                        onChange={(e) =>
                                            setData(
                                                "labolatory_id",
                                                e.target.value
                                            )
                                        }
                                        disabled={auth.user.role === 'laboran'}
                                    >
                                        <option value="">
                                            Pilih Laboratorium
                                        </option>
                                        {laboratories.map((lab) => (
                                            <option key={lab.id} value={lab.id}>
                                                {lab.name}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.labolatory_id && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.labolatory_id}
                                        </div>
                                    )}
                                </div>

                                <div className="col-span-2">
                                    <label className="block text-sm font-medium text-gray-700">
                                        Informasi
                                    </label>
                                    <textarea
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                        rows="3"
                                        value={data.information}
                                        onChange={(e) =>
                                            setData(
                                                "information",
                                                e.target.value
                                            )
                                        }
                                    ></textarea>
                                    {errors.information && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.information}
                                        </div>
                                    )}
                                </div>

                                <div className="col-span-2">
                                    {/* Show existing images */}
                                    <div className="grid grid-cols-4 gap-4 mb-4">
                                        {inventory.galleries?.map(gallery => (
                                            <div key={gallery.id} className="relative group">
                                                <img 
                                                    src={`/storage/${gallery.filepath}`}
                                                    className="w-full h-32 object-cover rounded cursor-pointer transition-transform hover:scale-105"
                                                    alt={gallery.filename}
                                                    onClick={() => openModal(gallery.filepath)}
                                                />
                                                <button
                                                    onClick={() => handleDeleteImage(gallery.id)}
                                                    className="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                                                    type="button"
                                                >
                                                    <Trash2 className="w-4 h-4" />
                                                </button>
                                            </div>
                                        ))}
                                    </div>

                                    {/* Upload new images */}
                                    <label className="block text-sm font-medium text-gray-700">Add More Images</label>
                                    <input
                                        type="file"
                                        multiple
                                        onChange={handleImageUpload}
                                        accept="image/*"
                                        className="mt-1 block w-full"
                                    />
                                    {errors.gallery && <div className="text-red-500 text-sm mt-1">{errors.gallery}</div>}
                                </div>
                            </div>

                            <div className="mt-6 flex justify-end space-x-4">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                >
                                    Update Inventaris
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
