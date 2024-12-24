<x-layout-dokter>
    <div class="p-4 mt-14">
        <!-- Judul H1 -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Pasien</h1>

        <!-- Bar untuk Search dan Tambah Jadwal -->
        <div class="flex justify-between items-center mb-4">
            <!-- Input Pencarian -->
            <div class="relative">
                <input
                    type="text"
                    id="search"
                    class="block p-2 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Cari jadwal..."
                />
                <svg
                    class="absolute top-1/2 left-2 w-5 h-5 text-gray-400 transform -translate-y-1/2"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-4.35-4.35m0 0a7.5 7.5 0 1110.15-10.15 7.5 7.5 0 01-10.15 10.15z"
                    />
                </svg>
            </div>

            <!-- Tombol Tambah Jadwal -->
            <button
class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center space-x-2"
type="button"
>
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
</svg>
<span>Tambah Jadwal</span>
</button>

        </div>

        <!-- Tabel -->
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Nama Pasien</th>
                        <th scope="col" class="px-6 py-3">Alamat</th>
                        <th scope="col" class="px-6 py-3">No. KTP</th>
                        <th scope="col" class="px-6 py-3">No. Telepon</th>
                        <th scope="col" class="px-6 py-3">No. RM</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            Apple MacBook Pro 17"
                        </th>
                        <td class="px-6 py-4">Silver</td>
                        <td class="px-6 py-4">Laptop</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">$2999</td>
                        <td class="flex items-center px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                            <a href="#" class="font-medium text-red-600 hover:underline ml-3">Remove</a>
                        </td>
                    </tr>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            Dell XPS 13
                        </th>
                        <td class="px-6 py-4">Black</td>
                        <td class="px-6 py-4">Laptop</td>
                        <td class="px-6 py-4">No</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">$1599</td>
                        <td class="flex items-center px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                            <a href="#" class="font-medium text-red-600 hover:underline ml-3">Remove</a>
                        </td>
                    </tr>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            HP Spectre x360
                        </th>
                        <td class="px-6 py-4">Grey</td>
                        <td class="px-6 py-4">Laptop</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">$1799</td>
                        <td class="flex items-center px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                            <a href="#" class="font-medium text-red-600 hover:underline ml-3">Remove</a>
                        </td>
                    </tr>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            Lenovo ThinkPad X1
                        </th>
                        <td class="px-6 py-4">Black</td>
                        <td class="px-6 py-4">Laptop</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">No</td>
                        <td class="px-6 py-4">$1399</td>
                        <td class="flex items-center px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                            <a href="#" class="font-medium text-red-600 hover:underline ml-3">Remove</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-layout-dokter>

