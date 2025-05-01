import { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { 
  Chart as ChartJS, 
  CategoryScale, 
  LinearScale, 
  PointElement, 
  LineElement, 
  BarElement,
  ArcElement,
  Title, 
  Tooltip, 
  Legend,
  Filler
} from 'chart.js';
import { Line, Bar, Doughnut } from 'react-chartjs-2';

// Register ChartJS components
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  ArcElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

export default function Dashboard({ auth, statistics, laboratories, conditions, monthlyData, topLabs }) {
    // Convert monthlyData (numeric keys) to array for chart
    const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const monthlyChartData = Object.values(monthlyData);

    // Prepare data for condition chart
    const conditionLabels = conditions.map(item => item.condition);
    const conditionCounts = conditions.map(item => item.total);
    const conditionColors = [
        'rgba(75, 192, 192, 0.7)',  // Good - Teal
        'rgba(255, 206, 86, 0.7)',  // Used - Yellow
        'rgba(255, 99, 132, 0.7)',  // Bad - Red
        'rgba(153, 102, 255, 0.7)', // Other - Purple
        'rgba(54, 162, 235, 0.7)',  // Other - Blue
    ];

    // Format number to add thousands separator
    const formatNumber = (num) => {
        return new Intl.NumberFormat('id-ID').format(num);
    };

    // Format currency
    const formatCurrency = (num) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(num);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                    {/* Overview Stats */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                        <StatCard 
                            title="Total Laboratorium" 
                            value={statistics.totalLabs} 
                            icon="🏫"
                            color="bg-blue-500"
                        />
                        <StatCard 
                            title="Total Ruangan" 
                            value={statistics.totalRooms} 
                            icon="🚪"
                            color="bg-green-500"
                        />
                        <StatCard 
                            title="Total Inventaris" 
                            value={formatNumber(statistics.totalInventories)} 
                            icon="📦"
                            color="bg-purple-500"
                        />
                        <StatCard 
                            title="Total Pengadaan" 
                            value={statistics.totalPengadaan} 
                            icon="📋"
                            color="bg-yellow-500"
                        />
                        <StatCard 
                            title="Nilai Inventaris" 
                            value={formatCurrency(statistics.totalInventoryValue)} 
                            icon="💰"
                            color="bg-red-500"
                        />
                    </div>

                    {/* Charts Row */}
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        {/* Monthly Pengadaan Chart */}
                        <div className="bg-white p-6 rounded-lg shadow-sm">
                            <h3 className="text-lg font-semibold mb-4">Nilai Pengadaan Bulanan (Tahun {new Date().getFullYear()})</h3>
                            <div className="h-80">
                                <Bar 
                                    data={{
                                        labels: monthLabels,
                                        datasets: [{
                                            label: 'Nilai Pengadaan',
                                            data: monthlyChartData,
                                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                            borderColor: 'rgb(54, 162, 235)',
                                            borderWidth: 1
                                        }]
                                    }}
                                    options={{
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        return formatCurrency(context.raw);
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: function(value) {
                                                        if (value >= 1000000) {
                                                            return (value / 1000000) + ' Juta';
                                                        }
                                                        return value;
                                                    }
                                                }
                                            }
                                        }
                                    }}
                                />
                            </div>
                        </div>

                        {/* Inventory Condition Chart */}
                        <div className="bg-white p-6 rounded-lg shadow-sm">
                            <h3 className="text-lg font-semibold mb-4">Kondisi Inventaris</h3>
                            <div className="h-80 flex items-center justify-center">
                                <Doughnut
                                    data={{
                                        labels: conditionLabels,
                                        datasets: [{
                                            data: conditionCounts,
                                            backgroundColor: conditionColors,
                                            hoverOffset: 4
                                        }]
                                    }}
                                    options={{
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: 'bottom',
                                            }
                                        }
                                    }}
                                />
                            </div>
                        </div>
                    </div>

                    {/* Laboratory Overview */}
                    <div className="bg-white p-6 rounded-lg shadow-sm mb-6">
                        <h3 className="text-lg font-semibold mb-4">Ringkasan Laboratorium</h3>
                        <div className="overflow-x-auto">
                            <table className="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th className="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Nama Laboratorium</th>
                                        <th className="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Jumlah Ruangan</th>
                                        <th className="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Jumlah Inventaris</th>
                                        <th className="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Rasio Inventaris/Ruangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {laboratories.map((lab) => (
                                        <tr key={lab.id} className="hover:bg-gray-50">
                                            <td className="py-4 px-4 border-b border-gray-200 text-sm">{lab.name}</td>
                                            <td className="py-4 px-4 border-b border-gray-200 text-sm">{lab.roomCount}</td>
                                            <td className="py-4 px-4 border-b border-gray-200 text-sm">{lab.inventoryCount}</td>
                                            <td className="py-4 px-4 border-b border-gray-200 text-sm">
                                                {lab.roomCount > 0 
                                                    ? (lab.inventoryCount / lab.roomCount).toFixed(2) 
                                                    : 'N/A'}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Top Labs by Inventory */}
                    <div className="bg-white p-6 rounded-lg shadow-sm">
                        <h3 className="text-lg font-semibold mb-4">Laboratorium dengan Inventaris Terbanyak</h3>
                        <div className="h-80">
                            <Bar 
                                data={{
                                    labels: topLabs.map(lab => lab.name),
                                    datasets: [{
                                        label: 'Jumlah Inventaris',
                                        data: topLabs.map(lab => lab.inventories_count),
                                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                                        borderColor: 'rgb(153, 102, 255)',
                                        borderWidth: 1
                                    }]
                                }}
                                options={{
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    indexAxis: 'y',
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                        }
                                    }
                                }}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

// Reusable stat card component
function StatCard({ title, value, icon, color }) {
    return (
        <div className="bg-white p-6 rounded-lg shadow-sm flex items-center">
            <div className={`${color} text-white rounded-full p-3 mr-4`}>
                <span className="text-2xl">{icon}</span>
            </div>
            <div>
                <h3 className="text-sm text-gray-500 uppercase">{title}</h3>
                <p className="text-2xl font-bold">{value}</p>
            </div>
        </div>
    );
}
