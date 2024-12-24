<x-layout-dokter>
    <div class="p-4 mt-14">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Judul H1 -->
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Memeriksa Pasien</h1>

        <!-- Tabel -->
        <div class="max-w-4xl mx-auto my-8 p-6 bg-white shadow-lg rounded-lg">
            <form method="POST" action="{{ route('periksa.store') }}" enctype="multipart/form-data" id="periksa-form">
                @csrf

                <div class="space-y-12">
                    <!-- Nama Pasien -->
                    <div class="sm:col-span-6">
                        <label for="nama_pasien" class="block text-sm font-medium text-gray-900">Nama Pasien</label>
                        <div class="mt-2">
                            <input
                                type="text"
                                name="nama"
                                id="nama"
                                value="{{ $pasien->pasien->nama ?? 'Data pasien tidak ditemukan' }}"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                readonly>
                        </div>
                    </div>

                    <!-- Input Hidden untuk ID Daftar Poli -->
                    <input
                        type="hidden"
                        name="id_daftar_poli"
                        value="{{ $pasien->id }}"> <!-- Pastikan $pasien->id mengarah ke ID Daftar Poli -->
                        @if(!$pasien->id)
    <p class="text-red-500">ID Daftar Poli tidak ditemukan!</p>
@endif

                </div>



               <!-- Tanggal Pemeriksaan -->
<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">
        <label for="tgl-periksa" class="block text-sm font-medium text-gray-900">Tanggal Pemeriksaan</label>
        <div class="mt-2">
            <!-- Input untuk Tanggal Pemeriksaan -->
            <input
                type="datetime-local"
                name="tgl_periksa"
                id="tgl_periksa"
                value="{{ old('tgl_periksa', date('Y-m-d\TH:i')) }}"
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
            >
        </div>
    </div>
</div>


                  <!-- Catatan Pemeriksaan -->
                  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                      <label for="catatan" class="block text-sm font-medium text-gray-900">Catatan Pemeriksaan</label>
                      <div class="mt-2">
                        <textarea name="catatan" id="catatan" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"></textarea>
                      </div>
                    </div>
                  </div>

                  <!-- Obat -->
                  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="id_obat" class="block text-sm font-medium text-gray-900">Obat</label>
                        <div class="mt-2">
                            <select name="id_obat" id="id_obat" onchange="updateBiayaPeriksa()" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                                <option value="" disabled selected>Pilih Obat</option>
                                @foreach ($obatList as $obat)
                                    <option value="{{ $obat->id }}" data-harga="{{ $obat->harga }}" data-kemasan="{{ $obat->kemasan }}"
                                        >{{ $obat->nama_obat }} - {{ $obat->kemasan }} (Rp{{ number_format($obat->harga, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Biaya Pemeriksaan -->
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="biaya-periksa" class="block text-sm font-medium text-gray-900">Biaya Pemeriksaan</label>
                        <div class="mt-2">
                            <input type="number" name="biaya_periksa" id="biaya-periksa" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" readonly>
                        </div>
                    </div>
                </div>



                <div class="mt-6 flex items-center justify-end gap-x-6">
                  <button type="button" class="text-sm font-semibold text-gray-900">Cancel</button>
                  <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                </div>
              </div>
            </form>
          </div>


</div>
</div>

<script>
    function updateBiayaPeriksa() {
    // Ambil harga obat yang dipilih
    var obatSelect = document.getElementById("id_obat");
    var selectedOption = obatSelect.options[obatSelect.selectedIndex];
    var biayaObat = selectedOption ? parseFloat(selectedOption.getAttribute("data-harga")) : 0;

    // Biaya dokter tetap
    var biayaDokter = 150000;

    // Hitung total biaya pemeriksaan
    var biayaPeriksa = biayaObat + biayaDokter;

    // Update input Biaya Pemeriksaan
    document.getElementById("biaya-periksa").value = biayaPeriksa;
}

// document.getElementById('periksa-form').addEventListener('submit', function(event) {
//         // Mengambil nilai dari input datetime-local
//         var tglPeriksa = document.getElementById('tgl_periksa').value;

//         // Mengubah format dari Y-m-d\TH:i ke Y-m-d H:i:s
//         if (tglPeriksa) {
//             var formattedDate = tglPeriksa.replace('T', ' ') + ":00"; // Menambahkan detik ":00"
//             document.getElementById('tgl_periksa').value = formattedDate; // Update nilai input
//         }
//     });

// function addSecondsToDate() {
//     var tglPeriksa = document.getElementById('tgl_periksa').value;
//     if (tglPeriksa && tglPeriksa.length === 16) { // jika panjangnya hanya Y-m-d H:i
//         document.getElementById('tgl_periksa').value = tglPeriksa + ":00"; // Menambahkan detik (00)
//     }
// }

</script>

</x-layout-dokter>
