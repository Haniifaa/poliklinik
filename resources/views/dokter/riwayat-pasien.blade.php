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
                    @forelse($riwayat as $r)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $loop->iteration }}</th>
                        <td class="px-6 py-4">{{ $r->daftarPoli->pasien->nama }}</td>
                        <td class="px-6 py-4">{{ $r->daftarPoli->pasien->alamat }}</td>
                        <td class="px-6 py-4">{{ $r->daftarPoli->pasien->no_ktp }}</td>
                        <td class="px-6 py-4">{{ $r->daftarPoli->pasien->no_hp }}</td>
                        <td class="px-6 py-4">{{ $r->daftarPoli->pasien->no_rm }}</td>
                        <td class="flex items-center px-6 py-4">
                            <button onclick="showModal({{ $r->id }})" class="font-medium text-blue-600 hover:underline" aria-label="Detail Riwayat Periksa">Detail Riwayat Periksa</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Tidak ada data pasien.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail Riwayat -->
    <div id="detailModal" class="fixed inset-0 z-50 hidden bg-gray-500 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-4xl w-full">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Riwayat Periksa</h2>
            <table class="min-w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Tanggal Periksa</th>
                        <th scope="col" class="px-6 py-3">Nama Pasien</th>
                        <th scope="col" class="px-6 py-3">Nama Dokter</th>
                        <th scope="col" class="px-6 py-3">Keluhan</th>
                        <th scope="col" class="px-6 py-3">Catatan</th>
                        <th scope="col" class="px-6 py-3">Obat</th>
                        <th scope="col" class="px-6 py-3">Biaya Periksa</th>
                    </tr>
                </thead>
                <tbody id="modal-detail-body">
                    <!-- Isi modal akan dimuat dengan JavaScript -->
                </tbody>
            </table>
            <div class="mt-4 flex justify-end">
                <button onclick="closeModal()" class="bg-purple-500 text-white py-2 px-4 rounded">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan modal dengan data yang sesuai
        function showModal(id) {
    const modal = document.getElementById('detailModal');
    const rows = document.querySelectorAll('#detailModal tbody tr');

    // Sembunyikan semua baris
    rows.forEach((row) => {
        row.classList.add('hidden');
    });

    // Ambil data riwayat yang sesuai
    const matchingRiwayat = @json($riwayat); // Menyertakan data riwayat pasien dari PHP ke JavaScript
    console.log("Data Riwayat:", matchingRiwayat); // Debugging: cek data riwayat
    console.log("ID yang dikirim:", id); // Debugging: cek ID yang dikirim

    // Cek ID dan data riwayat
    const riwayat = matchingRiwayat.find(r => parseInt(r.id) === parseInt(id)); // Memastikan tipe data sama (integer)

    // Debugging: cek apakah data riwayat ditemukan
    if (riwayat) {
        console.log("Data Riwayat yang Ditemukan:", riwayat); // Log data yang ditemukan
        const modalBody = document.getElementById('modal-detail-body');

        // Menggunakan optional chaining (?.) untuk menghindari error jika data tidak ada
        modalBody.innerHTML = `
            <tr>
                <td class="px-6 py-4">${id}</td>
                <td class="px-6 py-4">${riwayat.tgl_periksa || 'Tanggal tidak tersedia'}</td>
                <td class="px-6 py-4">${riwayat.daftarPoli?.pasien?.nama || 'Nama Pasien Tidak Ditemukan'}</td>
                <td class="px-6 py-4">${riwayat.daftarPoli?.dokter?.nama || 'Tidak ada dokter'}</td>
                <td class="px-6 py-4">${riwayat.daftarPoli?.keluhan || 'Tidak ada keluhan'}</td>
                <td class="px-6 py-4">${riwayat.catatan || 'Tidak ada catatan'}</td>
                <td class="px-6 py-4">
                    ${riwayat.detailPeriksa?.length ? riwayat.detailPeriksa.map(d => d.obat?.nama_obat).join('<br>') : 'Tidak ada obat'}
                </td>
                <td class="px-6 py-4">Rp ${riwayat.biaya_periksa?.toLocaleString() || '0'}</td>
            </tr>
        `;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    } else {
        console.error("Data riwayat tidak ditemukan atau tidak lengkap.");
        alert("Data riwayat tidak ditemukan.");
    }
}




        // Fungsi untuk menutup modal
        function closeModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</x-layout-dokter>
