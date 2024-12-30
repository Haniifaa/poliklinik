<x-layout-dokter>
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
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Periksa Pasien</h1>

        <!-- Bar untuk Search dan Tambah Pasien -->
        <div class="flex justify-between items-center mb-4">
            <!-- Input Pencarian -->
            <form method="GET" action="{{ route('dokter.periksa-pasien') }}" class="relative">
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

        </div>

        <!-- Tabel -->
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">No Urut</th>
                        <th scope="col" class="px-6 py-3">Nama Pasien</th>
                        <th scope="col" class="px-6 py-3">Keluhan</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periksa as $p)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $p->no_antrian }}</th>
                            <td class="px-6 py-4">{{ $p->pasien->nama ?? 'Tidak ada data' }}</td>
                            <td class="px-6 py-4">{{ $p->keluhan }}</td>
                            <td class="flex items-center px-6 py-4">
                                @if($p->id_periksa)
                                <!-- Jika pasien sudah diperiksa -->
                                <button onclick="editPeriksa({{ $p->id_periksa }})" class="font-medium text-blue-600 hover:underline" aria-label="Edit Periksa">Edit</button>
                            @else
                                <!-- Jika pasien belum diperiksa -->
                                <button onclick="window.location.href='{{ route('dokter.periksa', $p->id) }}'" class="font-medium text-green-600 hover:underline" aria-label="Periksa Pasien">Periksa</button>
                            @endif



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
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        readonly
                        required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <!-- No. KTP -->

                <input type="hidden" id="id_daftar_poli" name="id_daftar_poli" value="{{ $p->id_daftar_poli ?? '' }}">

                <!-- Tanggal Pemeriksaan dan Catatan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="tgl_periksa" class="block text-sm font-medium text-gray-900">Tanggal Pemeriksaan</label>
                    <input
                        type="datetime-local"
                        id="tgl_periksa"
                        name="tgl_periksa"
                        value="{{ old('tgl_periksa', $p->tgl_periksa) }}"
                        class="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-sm text-gray-900 border border-gray-300">
                </div>


                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-900">Catatan Pemeriksaan</label>
                    <textarea
                        id="catatan"
                        name="catatan"
                        rows="3"
                        class="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-sm text-gray-900 border border-gray-300">{{ old('catatan', $p->catatan) }}</textarea>
                </div>
            </div>

            <!-- Daftar Obat -->
            <div class="mt-4">
                <label for="obat-list" class="block text-sm font-medium text-gray-900">Obat</label>
                <div id="obat-list"></div>
                <button
                    type="button"
                    id="tambah-obat-btn"
                    onclick="tambahObat()"
                    class="mt-2 px-3 py-1 bg-purple-500 text-white text-sm rounded hover:bg-purple-600">
                    Tambah Obat
                </button>
            </div>

            <!-- Biaya Pemeriksaan -->
            <div class="mt-6">
                <label for="biaya_periksa" class="block text-sm font-medium text-gray-900">Biaya Pemeriksaan</label>
                <input
                    type="number"
                    name="biaya_periksa"
                    id="biaya_periksa"
                    class="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-sm text-gray-900 border border-gray-300"
                    readonly>
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


<script>
    let daftarObat = [];


// Ambil data obat dari elemen tersembunyi saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    const dataObatElement = document.getElementById('data-obat');
    if (dataObatElement) {
        try {
            daftarObat = JSON.parse(dataObatElement.textContent);
        } catch (error) {
            console.error('Error parsing data obat:', error.message);
        }
    }
});
function addObatRow(obatList, obat = null, index = 0) {
    const row = document.createElement('div');
    row.classList.add('flex', 'items-center', 'space-x-4', 'mt-2');

    const obatSelect = document.createElement('select');
    obatSelect.name = `obat[${index}][id]`;
    obatSelect.classList.add('w-full', 'px-3', 'py-1.5', 'border', 'rounded');

    // Tambah opsi default
    const emptyOption = document.createElement('option');
    emptyOption.value = '';
    emptyOption.textContent = '-- Pilih Obat --';
    obatSelect.appendChild(emptyOption);

    // Gunakan daftarObat global
    // daftarObat.forEach(item => {
    //     const option = document.createElement('option');
    //     option.value = item.id;
    //     option.textContent = `${item.nama_obat} | ${item.kemasan} | ${item.harga}`;
    //     option.dataset.harga = item.harga;
    //     if (obat && obat.id === item.id) {
    //         option.selected = true;
    //     }
    //     obatSelect.appendChild(option);
    // });
    // Tambahkan daftar obat ke dropdown
    if (daftarObat && daftarObat.length > 0) {
        daftarObat.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `${item.nama_obat} | ${item.kemasan} | ${item.harga}`;
            if (obat && obat.id === item.id) option.selected = true;
            option.dataset.harga = item.harga; // Simpan harga di dataset
            obatSelect.appendChild(option);
        });
    } else {
        const noDataOption = document.createElement('option');
        noDataOption.value = '';
        noDataOption.textContent = 'Data obat tidak tersedia';
        obatSelect.appendChild(noDataOption);
    }

    // Event listener untuk mengupdate biaya pemeriksaan
    obatSelect.addEventListener('change', () => {
        updateBiayaPeriksa();
    });

    // Tombol hapus
    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.classList.add('px-3', 'py-1', 'bg-red-500', 'text-white', 'rounded');
    deleteBtn.textContent = 'Hapus';
    deleteBtn.onclick = () => {
        row.remove();
        updateBiayaPeriksa(); // Update biaya periksa setelah menghapus
    };

    // Tambahkan elemen ke baris
    row.appendChild(obatSelect);
    row.appendChild(deleteBtn);

    // Tambahkan baris ke daftar obat
    obatList.appendChild(row);
}

function getDaftarObat() {
    return daftarObat; // Mengembalikan variabel `daftarObat` yang sudah diinisialisasi sebelumnya
}



function tambahObat() {
    const obatList = document.getElementById('obat-list');
    // const daftarObat = getDaftarObat(); // Fungsi untuk mendapatkan daftar obat
    const index = obatList.childElementCount; // Hitung jumlah baris saat ini
    addObatRow(obatList, null, index);
}




function updateBiayaPeriksa() {
    const obatDropdowns = document.querySelectorAll('#obat-list select');
    let totalBiaya = 0;

    // Hitung total biaya obat dari dropdown
    obatDropdowns.forEach(select => {
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.dataset.harga) {
            totalBiaya += parseFloat(selectedOption.dataset.harga);
        }
    });

    // Tambahkan biaya dokter sebesar 150,000
    const biayaDokter = 150000;
    totalBiaya += biayaDokter;

    // Update input biaya pemeriksaan tanpa desimal
    document.getElementById('biaya_periksa').value = Math.round(totalBiaya);
}


function editPeriksa(periksa) {
    console.log('ID Periksa yang dikirim ke server:', periksa);

    fetch(`/dokter/periksa-pasien/${periksa}/edit`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const periksa = data.data;

                daftarObat = periksa.daftar_obat;


                // Mengisi formulir dengan data pasien dan periksa
                document.getElementById('editForm').action = `/dokter/periksa-pasien/${periksa.id}`;
                document.getElementById('nama').value = periksa.nama; // Isi nama pasien
                document.getElementById('tgl_periksa').value = periksa.tgl_periksa;
                document.getElementById('catatan').value = periksa.catatan;
                document.getElementById('biaya_periksa').value = periksa.biaya_periksa;
                document.getElementById('id_daftar_poli').value = periksa.id_daftar_poli;

                // Mengisi daftar obat
                const obatList = document.getElementById('obat-list');
                obatList.innerHTML = ''; // Reset daftar obat
                periksa.obat.forEach((obat, index) => {
                    addObatRow(obatList, obat, index); // Kirim daftar obat
                });

                // Menampilkan modal
                openModal('editmodal');
            } else {
                alert(data.message || 'Data periksa tidak ditemukan.');
            }
        })
        .catch(error => {
            console.error('Error:', error.message);
            alert('Terjadi kesalahan saat mengambil data.');
        });
}





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

</script>

</x-layout-dokter>
