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


       <!-- Modal Edit Periksa Pasien -->
<div id="editmodal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <!-- Modal Container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6">
        <!-- Modal Header -->
        <div class="flex justify-between items-center border-b pb-3">
            <h2 class="text-xl font-bold text-gray-900">Edit Periksa</h2>
            <button type="button" class="text-gray-500 hover:text-red-500" onclick="closeModal('editmodal')">
                &times;
            </button>
        </div>

        <!-- Modal Body -->
        <form id="editmodal" method="POST" action="">
            @csrf
            @method('PUT')

            <!-- Nama Pasien -->

            <div class="mt-4">
                <label for="nama" class="block text-sm font-medium text-gray-900">Nama Pasien</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ $periksa->daftarPoli->pasien->nama ?? 'Data pasien tidak ditemukan' }}"
                    class="mt-2 block w-full rounded-md bg-gray-100 px-3 py-1.5 text-sm text-gray-900"
                    readonly>
            </div>


            <!-- Input Hidden untuk ID Daftar Poli -->
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
                    onclick="addObatRow()"
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

            <!-- Buttons -->
            <div class="flex justify-end mt-6 space-x-3">
                <button
                    type="button"
                    onclick="closeModal('editmodal')"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>



<script>
function addObatRow(obatList = document.getElementById('obat-list'), obat = null, index = 0) {
    const row = document.createElement('div');
    row.classList.add('flex', 'items-center', 'space-x-4', 'mt-2');

    // Input gabungan nama obat, kemasan, dan harga
    const obatInput = document.createElement('input');
    obatInput.type = 'text';
    obatInput.name = `obat[${index}]`;
    obatInput.placeholder = 'Nama Obat | Kemasan | Harga';
    obatInput.value = obat ? `${obat.nama_obat} | ${obat.kemasan} | ${obat.harga}` : '';
    obatInput.classList.add('w-full', 'px-3', 'py-1.5', 'border', 'rounded');

    // Tombol hapus
    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.classList.add('px-3', 'py-1', 'bg-red-500', 'text-white', 'rounded');
    deleteBtn.textContent = 'Hapus';
    deleteBtn.onclick = () => row.remove();

    // Tambahkan elemen ke baris
    row.appendChild(obatInput);
    row.appendChild(deleteBtn);

    // Tambahkan baris ke daftar obat
    obatList.appendChild(row);
}

function editPeriksa(periksa) {
    // Ambil data periksa berdasarkan ID
    console.log('ID Periksa yang dikirim ke server:', periksa);

    fetch(`/dokter/periksa-pasien/${periksa}/edit`)

        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const periksa = data.data;

                // Mengisi formulir dengan data pasien dan periksa
                document.getElementById('editmodal').action = `/dokter/periksa-pasien/${periksa}`;
                if (data.daftarPoli && data.daftarPoli.pasien) {
            document.getElementById('nama').value = data.daftarPoli.pasien.nama;
        } else {
            document.getElementById('nama').value = 'Data pasien tidak ditemukan';
        }
                document.getElementById('tgl_periksa').value = periksa.tgl_periksa;
                document.getElementById('catatan').value = periksa.catatan;
                document.getElementById('biaya_periksa').value = periksa.biaya_periksa;
                document.getElementById('id_daftar_poli').value = periksa.id_daftar_poli;

                // Mengisi daftar obat
                const obatList = document.getElementById('obat-list');
                obatList.innerHTML = ''; // Reset daftar obat
                periksa.obat.forEach((obat, index) => {
                    const obatData = {
                        nama_obat: obat.nama_obat,
                        kemasan: obat.kemasan,
                        harga: obat.harga,
                    };
                    addObatRow(obatList, obatData, index);
                });

                // Menampilkan modal
                openModal('editmodal');
            } else {
                alert('Data periksa tidak ditemukan.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil data.');
        });
}



function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}



    // // Fungsi untuk membuka modal
    // function openModal(modalId) {
    //     const modal = document.getElementById(modalId);
    //     if (modal) {
    //         modal.classList.remove('hidden');
    //     }
    // }

    // // Fungsi untuk menutup modal
    // function closeModal(modalId) {
    //     const modal = document.getElementById(modalId);
    //     if (modal) {
    //         modal.classList.add('hidden');
    //     }
    // }

    // // Fungsi untuk membersihkan modal
    // function clearModal() {
    //     document.getElementById('editForm').reset();
    //     document.getElementById('obat-list').innerHTML = '';
    //     document.getElementById('biaya_periksa').value = 0;
    // }

    // // Fungsi untuk menambahkan baris obat
    // function addObatRow() {
    //     const container = document.getElementById('obat-list');
    //     const index = container.children.length; // Gunakan panjang children untuk menentukan index
    //     const row = document.createElement('div');
    //     row.classList.add('flex', 'gap-4', 'mb-2');
    //     row.id = `obat-row-${index}`;

    //     row.innerHTML = `
    //         <select
    //             name="obat[${index}][id]"
    //             class="obat-select block w-full rounded-md bg-white px-3 py-1.5 text-sm text-gray-900 border border-gray-300"
    //             onchange="updateBiayaPeriksa()">
    //             <option value="" disabled selected>Pilih Obat</option>
    //             ${obatList.map(obat => `
    //                 <option value="${obat.id}" data-harga="${obat.harga}">
    //                     ${obat.nama} - Rp${obat.harga.toLocaleString('id-ID')}
    //                 </option>
    //             `).join('')}
    //         </select>
    //         <input
    //             type="number"
    //             name="obat[${index}][jumlah]"
    //             class="jumlah-obat-input block w-20 rounded-md bg-white px-3 py-1.5 text-sm text-gray-900 border border-gray-300"
    //             min="1"
    //             value="1"
    //             oninput="updateBiayaPeriksa()">
    //         <button
    //             type="button"
    //             class="hapus-obat-btn text-red-500 hover:underline"
    //             onclick="removeObatRow(${index})">
    //             Hapus
    //         </button>
    //     `;

    //     container.appendChild(row);
    // }

    // // Fungsi untuk menghapus baris obat
    // function removeObatRow(index) {
    //     const row = document.getElementById(`obat-row-${index}`);
    //     if (row) {
    //         row.remove();
    //         updateBiayaPeriksa();
    //     }
    // }

    // // Fungsi untuk menghapus baris obat dan menghitung ulang biaya
    // function attachEventListeners() {
    //     document.querySelectorAll('.remove-obat').forEach(button => {
    //         button.addEventListener('click', function () {
    //             this.parentElement.remove();
    //             updateBiayaPeriksa();
    //         });
    //     });

    //     document.querySelectorAll('.obat-select, .obat-jumlah').forEach(element => {
    //         element.addEventListener('change', updateBiayaPeriksa);
    //     });
    // }



    // Fungsi untuk mengedit data periksa
//     function editperiksa(periksa) {
//     // Bersihkan modal sebelum diisi ulang
//     clearModal();

//     // Fetch data dari endpoint
//     fetch(`/dokter/periksa-pasien/${periksa}/edit`)
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Data tidak ditemukan');
//             }
//             return response.json();
//         })
//         .then(data => {
//             // Isi data ke dalam form modal
//             const form = document.getElementById('editForm');
//             if (form) form.action = `/dokter/periksa-pasien/${periksa}`; // Update action form

//             // Tampilkan nama pasien (tidak bisa diedit)
//             const namaInput = document.getElementById('nama');
// if (namaInput) namaInput.value = data.pasien?.nama || 'Nama tidak ditemukan';

//             const tgl_periksa = document.getElementById('tgl_periksa');
//             if (tgl_periksa) tgl_periksa.value = data.tgl_periksa?.replace(' ', 'T') || '';

//             const catatan = document.getElementById('catatan');
//             if (catatan) catatan.value = data.catatan || '';

//             const biaya_periksa = document.getElementById('biaya_periksa');
//             if (biaya_periksa) biaya_periksa.value = data.biaya_periksa || 0;

//             // Bersihkan container obat
//             const container = document.getElementById('obat-container');
//             if (container) {
//                 container.innerHTML = '';

//                 // Tambahkan obat ke dalam modal
//                 data.obat?.forEach((item, index) => {
//                     const newRow = document.createElement('div');
//                     newRow.classList.add('flex', 'gap-4', 'mb-2');
//                     newRow.id = `obat-group-${index}`;

//                     newRow.innerHTML = `
//                         <select
//                             name="obat[${index}][id]"
//                             class="obat-select block w-full rounded-md bg-white px-3 py-1.5 text-sm text-gray-900 border border-gray-300">
//                             <option value="" disabled>Pilih Obat</option>
//                             ${data.obatList.map(obat => `
//                                 <option value="${obat.id}" ${item.id === obat.id ? 'selected' : ''}>
//                                     ${obat.nama} - Rp${obat.harga.toLocaleString('id-ID')}
//                                 </option>
//                             `).join('')}
//                         </select>
//                         <input
//                             type="number"
//                             name="obat[${index}][jumlah]"
//                             class="obat-jumlah block w-20 rounded-md bg-white px-3 py-1.5 text-sm text-gray-900 border border-gray-300"
//                             min="1"
//                             value="${item.jumlah}">
//                         <button
//                             type="button"
//                             class="hapus-obat-btn text-red-500 hover:underline"
//                             data-id="${index}">
//                             Hapus
//                         </button>
//                     `;
//                     container.appendChild(newRow);
//                 });
//             }

//             // Buka modal setelah semua data terisi
//             openModal('editmodal');
//         })
//         .catch(error => {
//             console.error('Error fetching periksa data:', error);
//         });
// }

// Fungsi untuk memasang event listener pada elemen dinamis
// function attachDynamicListeners() {
//     // Event listener untuk tombol hapus
//     document.getElementById("obat-container").addEventListener("click", function(e) {
//         if (e.target.classList.contains("hapus-obat-btn")) {
//             var id = e.target.getAttribute("data-id");
//             var group = document.getElementById(`obat-group-${id}`);
//             if (group) {
//                 group.remove();
//                 updateBiayaPeriksa();
//             }
//         }
//     });

//     // Event listener untuk perubahan input jumlah atau pilihan obat
//     document.getElementById("obat-container").addEventListener("input", function(e) {
//         if (e.target.classList.contains("jumlah-obat-input") || e.target.classList.contains("obat-select")) {
//             updateBiayaPeriksa();
//         }
//     });
// }

// // Fungsi untuk memperbarui biaya pemeriksaan
// // Fungsi untuk memperbarui biaya pemeriksaan
// function updateBiayaPeriksa() {
//         const biayaDokter = 150000; // Biaya tetap dokter
//         let totalBiayaObat = 0;

//         document.querySelectorAll('#obat-list .obat-select').forEach((select, index) => {
//             const selectedOption = select.options[select.selectedIndex];
//             const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
//             const jumlah = parseInt(document.querySelectorAll('#obat-list .jumlah-obat-input')[index].value) || 0;

//             totalBiayaObat += harga * jumlah;
//         });

//         // Total biaya pemeriksaan
//         const totalBiaya = biayaDokter + totalBiayaObat;
//         document.getElementById('biaya_periksa').value = totalBiaya;
//     }

//     // Inisialisasi event listener
//     attachEventListeners();

//     // Fungsi untuk memuat data periksa
//     function editPeriksa(periksa) {
//         clearModal();

//         fetch(`/dokter/periksa-pasien/${periksa}/edit`)
//             .then(response => {
//                 if (!response.ok) throw new Error('Data tidak ditemukan');
//                 return response.json();
//             })
//             .then(data => {
//                 document.getElementById('editForm').action = `/dokter/periksa-pasien/${periksa}`;
//                 document.getElementById('nama').value = data.pasien?.nama || 'Nama tidak ditemukan';
//                 document.getElementById('tgl_periksa').value = data.tgl_periksa?.replace(' ', 'T') || '';
//                 document.getElementById('catatan').value = data.catatan || '';
//                 document.getElementById('biaya_periksa').value = data.biaya_periksa || 0;

//                 const container = document.getElementById('obat-list');
//                 container.innerHTML = ''; // Bersihkan daftar obat

//                 data.obat?.forEach((item, index) => {
//                     addObatRow(); // Tambahkan baris obat baru
//                     const newRow = container.lastElementChild;

//                     const select = newRow.querySelector('.obat-select');
//                     const inputJumlah = newRow.querySelector('.jumlah-obat-input');

//                     select.value = item.id;
//                     inputJumlah.value = item.jumlah;
//                 });

//                 openModal('editmodal');
//             })
//             .catch(error => console.error('Error fetching periksa data:', error));
//     }
</script>

</x-layout-dokter>
