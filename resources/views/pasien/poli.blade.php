<x-layout-pasien>
    <div class="p-4 mt-14">
        <!-- Judul H1 -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Poli</h1>

        <div class="max-w-md mx-auto bg-white border border-gray-200 rounded-lg shadow-lg p-6">
             <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Daftar Poli</h2>

             <form action="{{ route('pasien.daftar-poli') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label for="no_rm" class="block mb-2 text-sm font-medium text-gray-900">No Rekam Medis</label>
                    <input
                        type="text"
                        id="no_rm"
                        name="no_rm"
                        value="{{ $no_rm }}"
                        class="bg-gray-200 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="No Rekam Medis"
                        readonly
                        disabled
                        required
                    />
                </div>



                <div class="mb-5">
                  <label for="poli" class="block mb-2 text-sm font-medium text-gray-900">Pilih Poli</label>
                  <select id="poli" name="poli" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    <option value="">Pilih Poli</option>
                    @foreach($poli as $p)
                      <option value="{{ $p->id }}" >{{ $p->nama_poli }}</option>
                    @endforeach
                  </select>
                </div>

                <!-- Pilih Jadwal -->
<div class="mb-5">
    <label for="jadwal" class="block mb-2 text-sm font-medium text-gray-900">Pilih Jadwal</label>
    <select id="jadwal" name="id_jadwal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
        <option value="">Pilih Jadwal</option>
        @foreach($jadwals as $jadwal)
            <option value="{{ $jadwal->id }}" data-poli="{{ $jadwal->dokter->poli->id ?? '' }}">
                {{ $jadwal->dokter->nama }} - {{ $jadwal->hari }}
                ({{ $jadwal->jam_mulai }} sampai {{ $jadwal->jam_selesai }})
            </option>
        @endforeach
    </select>
</div>


                <div class="mb-5">
                  <label for="keluhan" class="block mb-2 text-sm font-medium text-gray-900">Keluhan</label>
                  <textarea id="keluhan" name="keluhan" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Tulis keluhan Anda" required></textarea>
                </div>

                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                  Daftar
                </button>
              </form>

          </div>

    </div>

    <div class="max-w-full mx-auto bg-white border border-gray-200 rounded-lg shadow-lg p-6">
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
            <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Riwayat Daftar Poli</h2>

            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Product name</th>
                        <th scope="col" class="px-6 py-3">Color</th>
                        <th scope="col" class="px-6 py-3">Category</th>
                        <th scope="col" class="px-6 py-3">Accessories</th>
                        <th scope="col" class="px-6 py-3">Available</th>
                        <th scope="col" class="px-6 py-3">Price</th>
                        <th scope="col" class="px-6 py-3">Weight</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            Apple MacBook Pro 17"
                        </th>
                        <td class="px-6 py-4">Silver</td>
                        <td class="px-6 py-4">Laptop</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">$2999</td>
                        <td class="px-6 py-4">3.0 lb.</td>
                        <td class="flex items-center px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                            <a href="#" class="font-medium text-red-600 hover:underline ml-3">Remove</a>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            Dell XPS 13
                        </th>
                        <td class="px-6 py-4">Black</td>
                        <td class="px-6 py-4">Laptop</td>
                        <td class="px-6 py-4">No</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">$1599</td>
                        <td class="px-6 py-4">2.6 lb.</td>
                        <td class="flex items-center px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                            <a href="#" class="font-medium text-red-600 hover:underline ml-3">Remove</a>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            HP Spectre x360
                        </th>
                        <td class="px-6 py-4">Grey</td>
                        <td class="px-6 py-4">Laptop</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">$1799</td>
                        <td class="px-6 py-4">2.9 lb.</td>
                        <td class="flex items-center px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                            <a href="#" class="font-medium text-red-600 hover:underline ml-3">Remove</a>
                        </td>
                    </tr>

                    <!-- Row 4 -->
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            Lenovo ThinkPad X1
                        </th>
                        <td class="px-6 py-4">Black</td>
                        <td class="px-6 py-4">Laptop</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">No</td>
                        <td class="px-6 py-4">$1399</td>
                        <td class="px-6 py-4">3.2 lb.</td>
                        <td class="flex items-center px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                            <a href="#" class="font-medium text-red-600 hover:underline ml-3">Remove</a>
                        </td>
                    </tr>

                    <!-- Additional Rows for longer table -->
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            Asus ZenBook 14
                        </th>
                        <td class="px-6 py-4">Blue</td>
                        <td class="px-6 py-4">Laptop</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">$1499</td>
                        <td class="px-6 py-4">2.7 lb.</td>
                        <td class="flex items-center px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                            <a href="#" class="font-medium text-red-600 hover:underline ml-3">Remove</a>
                        </td>
                    </tr>

                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            Microsoft Surface Laptop 3
                        </th>
                        <td class="px-6 py-4">Platinum</td>
                        <td class="px-6 py-4">Laptop</td>
                        <td class="px-6 py-4">No</td>
                        <td class="px-6 py-4">Yes</td>
                        <td class="px-6 py-4">$1299</td>
                        <td class="px-6 py-4">2.8 lb.</td>
                        <td class="flex items-center px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                            <a href="#" class="font-medium text-red-600 hover:underline ml-3">Remove</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    </div>


    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const poliSelect = document.getElementById('poli');
    const jadwalSelect = document.getElementById('jadwal');
    const jadwalOptions = Array.from(jadwalSelect.options); // Simpan semua opsi jadwal

    poliSelect.addEventListener('change', function () {
        const selectedPoli = this.value;

        // Reset opsi jadwal
        jadwalSelect.innerHTML = '<option value="">Pilih Jadwal</option>';

        // Filter jadwal berdasarkan poli yang dipilih
        const filteredOptions = jadwalOptions.filter(option => option.dataset.poli === selectedPoli);

        // Tambahkan opsi yang difilter ke dropdown
        filteredOptions.forEach(option => {
            jadwalSelect.appendChild(option.cloneNode(true));
        });
    });
});

</script>

</x-layout-pasien>
