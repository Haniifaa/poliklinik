<x-layout-dokter>
        <div class="p-4 mt-14">
            <!-- Judul H1 -->
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Jadwal Periksa</h1>

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
                type="button"
                onclick="openModal('tambahmodal')"
                class="text-white bg-purple-400 hover:bg-purple-500 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center space-x-2"
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
                            <th scope="col" class="px-6 py-3">Nama Dokter</th>
                            <th scope="col" class="px-6 py-3">Hari</th>
                            <th scope="col" class="px-6 py-3">Jam Mulai</th>
                            <th scope="col" class="px-6 py-3">Jam Selesai</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwals as $index => $jadwal)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $index + 1 }}
                            </th>
                            <td class="px-6 py-4">{{ $jadwal->dokter->nama }}</td>
                            <td class="px-6 py-4">{{ $jadwal->hari }}</td>
                            <td class="px-6 py-4">{{ $jadwal->jam_mulai }}</td>
                            <td class="px-6 py-4">{{ $jadwal->jam_selesai }}</td>
                            <td class="px-6 py-4 flex items-center">
                                <div class="flex items-center me-2">
                                    <div id="status-indicator-{{ $jadwal->id }}" data-id="{{ $jadwal->id }}"
                                        class="h-2.5 w-2.5 rounded-full {{ $jadwal->status == 'Aktif' ? 'bg-green-500' : 'bg-red-500' }} me-2">
                                    </div>
                                </div>
                                <select onchange="updateStatus({{ $jadwal->id }}, this.value)"
                                        class="border border-gray-300 rounded p-1 focus:outline-none focus:ring focus:ring-blue-200">
                                    <option value="Aktif" {{ $jadwal->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ $jadwal->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </td>
                            <!-- Kolom Action -->
                            <td class="px-6 py-4">
                                <form action="{{ route('dokter.jadwal-periksa.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>



            <!-- Modal Tambah Jadwal Periksa -->
<div id="tambahmodal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-5">
        <h2 class="text-lg font-bold mb-3">Tambah Jadwal Periksa</h2>
        <form method="POST" action="{{ route('dokter.jadwal-periksa.store') }}">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">

                <!-- Nama Dokter (Otomatis) -->
                <div>
                    <label for="nama_dokter" class="block text-sm font-medium text-gray-700">Nama Dokter</label>
                    <input
                        type="text"
                        name="nama_dokter"
                        id="nama_dokter"
                        value="{{ session('dokter')->nama }}"
                        readonly
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                    />
                </div>

                <!-- Hari -->
                <div>
                    <label for="hari" class="block text-sm font-medium text-gray-700">Hari</label>
                    <select
                        name="hari"
                        id="hari"
                        required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                    >
                        <option value="" disabled selected>Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>

                <!-- Jam Mulai -->
                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                    <input
                        type="time"
                        name="jam_mulai"
                        id="jam_mulai"
                        required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                    />
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                    <input
                        type="time"
                        name="jam_selesai"
                        id="jam_selesai"
                        required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                    />
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select
                        name="status"
                        id="status"
                        required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                    >
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>

            </div>
            <div class="flex justify-end space-x-3 mt-4">
                <button
                    type="button"
                    onclick="closeModal('tambahmodal')"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700"
                >
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>


{{-- <!-- Modal Edit Jadwal Periksa -->
<div id="editmodal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6">
        <h2 class="text-xl font-bold mb-4">Edit Jadwal Periksa</h2>
        <form id="editJadwalForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <!-- Nama Dokter (Readonly) -->
<div>
    <label for="edit_nama_dokter" class="block text-sm font-medium text-gray-700">Nama Dokter</label>
    <input
        type="text"
        id="edit_nama_dokter"
        value="{{ session('dokter')->nama }}"
        readonly
        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-gray-100 cursor-not-allowed"
    />
</div>
<!-- Hari -->
<div>
    <label for="edit_hari" class="block text-sm font-medium text-gray-700">Hari</label>
    <select
        name="hari"
        id="edit_hari"
        required
        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
    >
        <option value="" disabled selected>Pilih Hari</option>
        <option value="Senin">Senin</option>
        <option value="Selasa">Selasa</option>
        <option value="Rabu">Rabu</option>
        <option value="Kamis">Kamis</option>
        <option value="Jumat">Jumat</option>
        <option value="Sabtu">Sabtu</option>
        <option value="Minggu">Minggu</option>
    </select>
</div>
                <!-- Jam Mulai -->
                <div>
                    <label for="edit_jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                    <input
                        type="time"
                        name="jam_mulai"
                        id="edit_jam_mulai"
                        required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                    />
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label for="edit_jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                    <input
                        type="time"
                        name="jam_selesai"
                        id="edit_jam_selesai"
                        required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                    />
                </div>

                <!-- Status -->
                <div>
                    <label for="edit_status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select
                        name="status"
                        id="edit_status"
                        required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                    >
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
                <button
                    type="button"
                    onclick="closeModal('editmodal')"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700"
                >
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div> --}}

        </div>



        <script>

            // Fungsi untuk membuka modal
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden'); // Hapus kelas 'hidden'
        modal.classList.add('flex');       // Tambahkan kelas 'flex' untuk menampilkan modal
    } else {
        console.error(`Modal dengan ID "${modalId}" tidak ditemukan.`);
    }
}

// Fungsi untuk menutup modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');  // Tambahkan kelas 'hidden'
        modal.classList.remove('flex'); // Hapus kelas 'flex'

        // Reset data input modal
        document.getElementById('editJadwalForm').reset();
    } else {
        console.error(`Modal dengan ID "${modalId}" tidak ditemukan.`);
    }
}



// Fungsi untuk membuka modal edit jadwal dengan data dari server
// Fungsi untuk membuka modal edit jadwal dengan data dari server
// function editJadwal(jadwal_periksa) {
//     // Panggil API untuk mendapatkan data jadwal
//     fetch(`/dokter/jadwal-periksa/${jadwal_periksa}/edit`)
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Gagal mengambil data dari server.');
//             }
//             return response.json();
//         })
//         .then(data => {
//             // Isi data dalam modal edit
//             document.getElementById('edit_jam_mulai').value = data.jam_mulai;
//             document.getElementById('edit_jam_selesai').value = data.jam_selesai;
//             document.getElementById('edit_status').value = data.status;
//             document.getElementById('edit_hari').value = data.hari; // Pastikan hari terisi dengan benar

//             // Perbarui action form
//             document.getElementById('editJadwalForm').action = `/dokter/jadwal-periksa/${jadwal_periksa}`;

//             // Tampilkan modal setelah data terisi
//             openModal('editmodal');
//         })
//         .catch(error => {
//             console.error('Terjadi kesalahan:', error);
//             alert('Gagal mengambil data jadwal.');
//         });
// }

// Ambil CSRF Token dari meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Fungsi untuk memperbarui status dan indikator status
function updateStatus(jadwal_periksa, newStatus) {
    // Pengecekan apakah ada jadwal lain yang aktif
    const allJadwal = document.querySelectorAll('.jadwal-status'); // Ganti dengan selector yang sesuai untuk status jadwal lainnya
    let isAnyActive = false;

    // Loop untuk memeriksa apakah ada jadwal dengan status 'Aktif'
    allJadwal.forEach(jadwal => {
        if (jadwal.textContent.trim() === 'Aktif') {
            isAnyActive = true;
        }
    });

    // Jika ada jadwal yang aktif dan ingin mengubah status menjadi 'Aktif', batalkan
    if (newStatus === 'Aktif' && isAnyActive) {
        alert('Hanya satu jadwal yang bisa aktif pada satu waktu.');
        return; // Jangan lanjutkan jika ada jadwal lain yang aktif
    }

    // Kirim permintaan POST untuk memperbarui status
    fetch(`/dokter/jadwal-periksa/${jadwal_periksa}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken // Gunakan CSRF Token dari meta tag
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Perbarui warna indikator berdasarkan status baru
            const indicator = document.getElementById(`status-indicator-${jadwal_periksa}`);
            if (indicator) {
                // Hapus kelas indikator lama
                indicator.classList.remove('bg-red-500', 'bg-green-500');
                // Tambahkan kelas indikator baru sesuai status
                setTimeout(() => {
                    indicator.classList.add(newStatus === 'Aktif' ? 'bg-green-500' : 'bg-red-500');
                }, 100); // Delay untuk memastikan refresh
            } else {
                console.error(`Indikator dengan ID "status-indicator-${jadwal_periksa}" tidak ditemukan.`);
            }
            alert('Status berhasil diperbarui');
        } else {
            alert('Gagal memperbarui status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan, coba lagi nanti');
    });
}



        </script>
    </x-layout-dokter>

