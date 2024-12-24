<x-layout-admin>
    <div class="p-4 mt-14">
        <!-- Display Alert Messages -->
        @if (session('success'))
            <div  id="successAlert" class="mb-4 p-4 bg-green-500 text-white rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-500 text-white rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- Judul H1 -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Data Pasien</h1>

        <!-- Bar untuk Search dan Tambah Pasien -->
        <div class="flex justify-between items-center mb-4">
            <!-- Input Pencarian -->
            <form method="GET" action="{{ route('admin.masterdata.pasien') }}" class="relative">
                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ request('search') }}"
                    class="block p-2 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Cari pasien..."
                />
                <button type="submit" class="absolute top-1/2 left-2 w-5 h-5 text-gray-400 transform -translate-y-1/2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0a7.5 7.5 0 1110.15-10.15 7.5 7.5 0 01-10.15 10.15z" />
                    </svg>
                </button>
            </form>

            <!-- Tombol Tambah Pasien -->
            <button
                type="button"
                onclick="openModal('tambahmodal')"
                class="text-white bg-purple-400 hover:bg-purple-500 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center space-x-2"
                >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Pasien</span>
            </button>

        </div>

        <!-- Tabel -->
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">Alamat</th>
                        <th scope="col" class="px-6 py-3">No KTP</th>
                        <th scope="col" class="px-6 py-3">No HP</th>
                        <th scope="col" class="px-6 py-3">No RM</th>
                        <th scope="col" class="px-6 py-3">Created At</th>
                        <th scope="col" class="px-6 py-3">Updated At</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pasien as $p)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $p->nama }}</th>
                            <td class="px-6 py-4">{{ $p->alamat }}</td>
                            <td class="px-6 py-4">{{ $p->no_ktp }}</td>
                            <td class="px-6 py-4">{{ $p->no_hp }}</td>
                            <td class="px-6 py-4">{{ $p->no_rm }}</td>
                            <td style="width: 200px;">
                                {{ optional($p->created_at)->format('d-m-Y H:i:s') ?? 'NULL' }}
                            </td>
                            <td style="width: 200px;">
                                {{ optional($p->updated_at)->format('d-m-Y H:i:s') ?? 'NULL' }}
                            </td>
                            <td class="flex items-center px-6 py-4">
                                <button onclick="editPasien({{ $p->id }})" class="font-medium text-blue-600 hover:underline" aria-label="Edit Pasien">Edit</button>
                                <form action="{{ route('admin.pasien.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline ml-3">Hapus</button>
                                </form>
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

        <!-- Pagination -->
        <div class="mt-4">
            {{ $pasien->links() }}
        </div>
    </div>

    <!-- Modal Tambah Pasien -->
    <div id="tambahmodal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-5">
            <h2 class="text-lg font-bold mb-3">Tambah Pasien</h2>
            <form method="POST" action="{{ route('admin.pasien.store') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">
                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    <!-- No. KTP -->
                    <div>
                        <label for="no_ktp" class="block text-sm font-medium text-gray-700">No. KTP</label>
                        <input
                            type="text"
                            name="no_ktp"
                            id="no_ktp"
                            required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    <!-- No. HP -->
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                        <input
                            type="text"
                            name="no_hp"
                            id="no_hp"
                            required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea
                            name="alamat"
                            id="alamat"
                            rows="2"
                            required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        ></textarea>
                    </div>
                    <!-- No. RM -->
                    <div class="sm:col-span-2">
                        <label for="no_rm" class="block text-sm font-medium text-gray-700">No. RM</label>
                        <input
                            type="text"
                            name="no_rm"
                            id="no_rm"
                            required
                            readonly
                            class="mt-1 block w-full p-2 border border-gray-300 bg-gray-200 text-gray-500 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 cursor-not-allowed"
                        />
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
                        class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>


        <!-- Modal Edit Pasien -->
        <div id="editmodal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-5">
                <h2 class="text-lg font-bold mb-3">Edit Pasien</h2>
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">
                        <!-- Nama -->
                        <div>
                            <label for="edit_nama" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input
                                type="text"
                                name="nama"
                                id="edit_nama"
                                required
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                        <!-- No. KTP -->
                        <div>
                            <label for="edit_no_ktp" class="block text-sm font-medium text-gray-700">No. KTP</label>
                            <input
                                type="text"
                                name="no_ktp"
                                id="edit_no_ktp"
                                required
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                        <!-- No. HP -->
                        <div>
                            <label for="edit_no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                            <input
                                type="text"
                                name="no_hp"
                                id="edit_no_hp"
                                required
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                        <!-- Alamat -->
                        <div>
                            <label for="edit_alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea
                                name="alamat"
                                id="edit_alamat"
                                rows="2"
                                required
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            ></textarea>
                        </div>
                        <!-- No. RM -->
                        <div class="sm:col-span-2">
                            <label for="edit_no_rm" class="block text-sm font-medium text-gray-700">No. RM</label>
                            <input
                                type="text"
                                name="no_rm"
                                id="edit_no_rm"
                                required
                                readonly
                                class="mt-1 block w-full p-2 border border-gray-300 bg-gray-200 text-gray-500 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 cursor-not-allowed"
                            />
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-4">
                        <button
                            type="button"
                            onclick="closeModal('editmodal')"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>



    <script>
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden'); // Menampilkan modal
    } else {
        console.error(`Modal dengan ID "${modalId}" tidak ditemukan.`);
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden'); // Menyembunyikan modal
    } else {
        console.error(`Modal dengan ID "${modalId}" tidak ditemukan.`);
    }
}

function editPasien(id) {
    // Ambil data pasien dan isi formulir
    fetch(`/admin/pasien/${id}/edit`)  // Sesuaikan URL dengan rute backend
        .then(response => response.json())
        .then(data => {
            // Isi kolom formulir dengan data pasien
            document.getElementById('editForm').action = `/admin/pasien/${id}`;  // Sesuaikan action form
            document.getElementById('edit_nama').value = data.nama;
            document.getElementById('edit_no_ktp').value = data.no_ktp;
            document.getElementById('edit_no_hp').value = data.no_hp;
            document.getElementById('edit_alamat').value = data.alamat;
            document.getElementById('edit_no_rm').value = data.no_rm;

            // Buka modal edit
            openModal('editmodal');
        })
        .catch(error => console.error('Error:', error));
}



        setTimeout(function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000); // 5000 ms = 5 detik

    </script>
</x-layout-admin>
