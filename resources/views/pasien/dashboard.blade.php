<x-layout-pasien>
    <div class="p-4  mt-14">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

        <div class="relative p-6 rounded-lg shadow-lg mb-8 z-10 bg-gradient-to-r from-white to-white">
            <!-- Circular Gradient Background (inside the box) -->
            <div class="absolute inset-0 rounded-lg bg-gradient-to-r from-white to-white overflow-hidden">
                <!-- Circular Gradients -->
                <div class="absolute -top-20 -left-20 w-96 h-96 bg-gradient-to-r from-white to-pink-500 rounded-full blur-3xl opacity-30"></div>
                <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full blur-3xl opacity-30"></div>

                <!-- Decorative Shapes -->
                <div class="absolute top-1/4 left-1/3 transform rotate-45 w-32 h-32 bg-purple-300/50 rounded-xl blur-xl"></div>
                <div class="absolute bottom-1/4 right-1/4 transform -rotate-45 w-40 h-40 bg-blue-300/50 rounded-full blur-lg"></div>
            </div>

            <!-- Greeting Content -->
            <div class="relative z-10">
                <h2 class="text-3xl font-semibold text-purple-500">Halo, {{ session('pasien')->nama }}!</h2>
                <p class="mt-2 text-md text-gray-600">Selamat datang kembali! Siap untuk mendaftar ke dokter dan mendapatkan layanan kesehatan terbaik? Ayo jelajahi dashboard Anda dan daftar untuk konsultasi hari ini.</p>
            </div>
        </div>


        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div class="flex items-center justify-between p-4 bg-white shadow rounded-lg">
                <div>
                    <h3 class="text-sm text-gray-500">Total Users</h3>
                    <p class="text-2xl font-bold text-gray-800">1,234</p>
                </div>
                <div class="text-blue-500">
                    <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8.009 8.009 0 0 1-8 8Z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 bg-white shadow rounded-lg">
                <div>
                    <h3 class="text-sm text-gray-500">New Orders</h3>
                    <p class="text-2xl font-bold text-gray-800">567</p>
                </div>
                <div class="text-green-500">
                    <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8.009 8.009 0 0 1-8 8Z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 bg-white shadow rounded-lg">
                <div>
                    <h3 class="text-sm text-gray-500">Revenue</h3>
                    <p class="text-2xl font-bold text-green-600">$12,345</p>
                </div>
                <div class="text-yellow-500">
                    <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8.009 8.009 0 0 1-8 8Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
            </div>
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-600">Order ID</th>
                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-600">Customer</th>
                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-600">Total</th>
                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="py-2 px-4">#1234</td>
                        <td class="py-2 px-4">John Doe</td>
                        <td class="py-2 px-4">$120.00</td>
                        <td class="py-2 px-4 text-green-600">Completed</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4">#1235</td>
                        <td class="py-2 px-4">Jane Smith</td>
                        <td class="py-2 px-4">$75.00</td>
                        <td class="py-2 px-4 text-yellow-600">Pending</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-layout-pasien>

