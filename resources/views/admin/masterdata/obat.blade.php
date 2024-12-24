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
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Data Obat</h1>

        <!-- Bar untuk Search dan Tambah Obat -->
        <div class="flex justify-between items-center mb-4">
            <!-- Input Pencarian -->
            <form method="GET" action="{{ route('admin.masterdata.obat') }}" class="relative">
                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ request('search') }}"
                    class="block p-2 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Cari obat..."
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
                <span>Tambah Obat</span>
            </button>

        </div>

        <!-- Tabel -->
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">Kemasan</th>
                        <th scope="col" class="px-6 py-3">Harga</th>
                        <th scope="col" class="px-6 py-3">Created At</th>
                        <th scope="col" class="px-6 py-3">Updated At</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($obat as $b)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $b->nama_obat }}</th>
                            <td class="px-6 py-4">{{ $b->kemasan }}</td>
                            <td class="px-6 py-4">{{ $b->harga }}</td>
                            <td style="width: 200px;">
                                {{ optional($b->created_at)->format('d-m-Y H:i:s') ?? 'NULL' }}
                            </td>
                            <td style="width: 200px;">
                                {{ optional($b->updated_at)->format('d-m-Y H:i:s') ?? 'NULL' }}
                            </td>
                            <td class="flex items-center px-6 py-4">
                                <button onclick="editObat({{ $b->id }})" class="font-medium text-blue-600 hover:underline" aria-label="Edit Obat">Edit</button>
                                <form action="{{ route('admin.obat.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data?')">
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
            {{ $obat->links() }}
        </div>
    </div>

    <!-- Modal Tambah Pasien -->
    <div id="tambahmodal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-5">
            <h2 class="text-lg font-bold mb-3">Tambah Obat</h2>
            <form method="POST" action="{{ route('admin.obat.store') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">
                    <!-- Nama -->
                    <div>
                        <label for="nama_obat" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input
                            type="text"
                            name="nama_obat"
                            id="nama_obat"
                            required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    <!-- No. KTP -->
                    <div>
                        <label for="kemasan" class="block text-sm font-medium text-gray-700">Kemasan</label>
                        <input
                            type="text"
                            name="kemasan"
                            id="kemasan"
                            required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    <!-- No. HP -->
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                        <input
                            type="number"
                            name="harga"
                            id="harga"
                            required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
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
                        <label for="edit_nama_obat" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input
                            type="text"
                            name="nama_obat"
                            id="edit_nama_obat"
                            required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    <!-- No. KTP -->
                    <div>
                        <label for="edit_kemasan" class="block text-sm font-medium text-gray-700">Kemasan</label>
                        <input
                            type="text"
                            name="kemasan"
                            id="edit_kemasan"
                            required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    <!-- No. HP -->
                    <div>
                        <label for="edit_harga" class="block text-sm font-medium text-gray-700">Harga</label>
                        <input
                            type="number"
                            name="harga"
                            id="edit_harga"
                            required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
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
        // Fungsi untuk membuka modal berdasarkan ID
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
    }
}

// Fungsi untuk menutup modal berdasarkan ID
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
}


// Fungsi untuk mengisi data dan membuka modal edit
function editObat(id) {
    const editModal = document.getElementById('editmodal');
    const editForm = document.getElementById('editForm');
    const editNamaObat = document.getElementById('edit_nama_obat');
    const editKemasan = document.getElementById('edit_kemasan');
    const editHarga = document.getElementById('edit_harga');

    // Simulasi pengambilan data (ganti ini dengan AJAX/Fetch sesuai kebutuhan)
    fetch(`/admin/obat/${id}/edit`) // Endpoint untuk mendapatkan data obat
        .then(response => response.json())
        .then(data => {
            // Isi form dengan data yang diterima
            editNamaObat.value = data.nama_obat;
            editKemasan.value = data.kemasan;
            editHarga.value = data.harga;

            // Update action pada form
            editForm.action = `/admin/obat/${id}`;

            // Buka modal edit
            openModal('editmodal');
        })
        .catch(error => console.error('Error fetching data:', error));
}



        setTimeout(function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000); // 5000 ms = 5 detik

    </script>
</x-layout-admin>
{{-- <x-layoutadmin>
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
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Data Obat</h1>

        <!-- Bar untuk Search dan Tambah Pasien -->
        <div class="flex justify-between items-center mb-4">
            <!-- Input Pencarian -->
            <form method="GET" action="{{ route('admin.masterdata.obat') }}" class="relative">
                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ request('search') }}"
                    class="block p-2 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Cari obat..."
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
                <span>Tambah Obat</span>
            </button>

        </div>

        <!-- Tabel -->
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Obat</th>
                        <th scope="col" class="px-6 py-3">Kemasan</th>
                        <th scope="col" class="px-6 py-3">Harga</th>
                        <th scope="col" class="px-6 py-3">Created At</th>
                        <th scope="col" class="px-6 py-3">Updated At</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($obat as $o)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $o->nama_obat }}</th>
                            <td class="px-6 py-4">{{ $o->kemasan }}</td>
                            <td class="px-6 py-4">{{ $o->harga }}</td>
                            <td style="width: 200px;">
                                {{ optional($o->created_at)->format('d-m-Y H:i:s') ?? 'NULL' }}
                            </td>
                            <td style="width: 200px;">
                                {{ optional($o->updated_at)->format('d-m-Y H:i:s') ?? 'NULL' }}
                            </td>
                            <td class="flex items-center px-6 py-4">
                                <button onclick="editObat({{ $o->id }})" class="font-medium text-blue-600 hover:underline" aria-label="Edit Obat">Edit</button>
                                <form action="{{ route('admin.obat.destroy', $o->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline ml-3">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Tidak ada data obat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <!-- Pagination -->
        <div class="mt-4">
            {{ $obat->links() }}
        </div>

    </div>

    <!-- Modal Tambah Pasien -->
    <div id="tambahmodal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden ">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6">
            <h2 class="text-xl font-bold mb-4">Tambah Obat</h2>
            <form method="POST" action="{{ route('admin.obat.store') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Nama Obat -->
                <div class="mb-4">
                    <label for="nama_obat" class="block text-sm font-medium text-gray-700">Nama Obat</label>
                    <input type="text" name="nama_obat" id="nama_obat" required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Kemasan -->
                <div class="mb-4">
                    <label for="kemasan" class="block text-sm font-medium text-gray-700">Kemasan</label>
                    <input type="text" name="kemasan" id="kemasan" required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Harga -->
                <div class="mb-4">
                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="number" name="harga" id="harga" required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('tambahmodal')" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

        <!-- Modal Edit Obat -->
        <div id="editmodal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-999999">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6">
                <h2 class="text-xl font-bold mb-4">Edit Obat</h2>
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Obat -->
                        <div class="mb-4">
                            <label for="edit_nama_obat" class="block text-sm font-medium text-gray-700">Nama Obat</label>
                            <input type="text" name="nama_obat" id="edit_nama_obat" required
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Kemasan -->
                        <div class="mb-4">
                            <label for="edit_kemasan" class="block text-sm font-medium text-gray-700">Kemasan</label>
                            <input type="text" name="kemasan" id="edit_kemasan" required
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Harga -->
                        <div class="mb-4">
                            <label for="edit_harga" class="block text-sm font-medium text-gray-700">Harga</label>
                            <input type="number" name="harga" id="edit_harga" required
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal('editmodal')" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>



    <script>
    /**
     * Fungsi untuk membuka modal berdasarkan ID
     * @param {string} modalId - ID dari modal yang ingin dibuka
     */
     function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    /**
     * Fungsi untuk menutup modal berdasarkan ID
     * @param {string} modalId - ID dari modal yang ingin ditutup
     */
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

        function editObat(id) {
            fetch(`/admin/obat/${id}/edit`)  // Sesuaikan URL dengan rute backend
                .then(response => response.json())
                .then(data => {
                    // Isi kolom formulir dengan data obat
                    if (data) {
                    // Isi kolom formulir dengan data dokter
                    const editForm = document.getElementById('editForm');
                    editForm.action = `/admin/obat/${id}`;  // Set action form untuk update

                    document.getElementById('edit_nama_obat').value = data.nama_obat;
                    document.getElementById('edit_kemasan').value = data.kemasan;
                    document.getElementById('edit_harga').value = data.harga;

                    // Buka modal edit
                    openModal('editmodal');
                    }
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
</x-layoutadmin> --}}
