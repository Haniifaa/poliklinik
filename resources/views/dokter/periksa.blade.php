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
    // Fungsi untuk menambah baris obat
function addObatRow() {
        var obatContainer = document.getElementById("obat-list");

        var uniqueId = Date.now();

        var obatHtml = `
    <div class="flex items-center gap-4 mb-4" id="obat-group-${uniqueId}">
        <select name="id_obat[]" class="obat-select block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" required>
            <option value="" disabled selected>Pilih Obat</option>
            @foreach ($obatList as $obat)
                <option value="{{ $obat->id }}" data-harga="{{ $obat->harga }}">
                    {{ $obat->nama_obat }} - {{ $obat->kemasan }} (Rp{{ number_format($obat->harga, 0, ',', '.') }})
                </option>
            @endforeach
        </select>
        <button type="button" class="hapus-obat-btn text-red-500 hover:underline" data-id="${uniqueId}">Hapus</button>
    </div>
`;

        obatContainer.insertAdjacentHTML("beforeend", obatHtml);
    }

    document.getElementById("obat-list").addEventListener("click", function(e) {
        if (e.target.classList.contains("hapus-obat-btn")) {
            var id = e.target.getAttribute("data-id");
            var group = document.getElementById(`obat-group-${id}`);
            if (group) {
                group.remove();
            }
        }
    });



// Event listener untuk input jumlah obat atau pilihan obat
document.getElementById("obat-list").addEventListener("input", function(e) {
    if (e.target.classList.contains("jumlah-obat-input") || e.target.classList.contains("obat-select")) {
        updateBiayaPeriksa();
    }
});

// Fungsi untuk menghitung biaya pemeriksaan
function updateBiayaPeriksa() {
    var biayaDokter = 150000; // Biaya tetap dokter
    var totalBiayaObat = 0;

    // Iterasi melalui semua select obat
    document.querySelectorAll("#obat-list .obat-select").forEach(function(select) {
        var selectedOption = select.options[select.selectedIndex];
        var harga = parseFloat(selectedOption.getAttribute("data-harga")) || 0;

        totalBiayaObat += harga; // Tambahkan harga obat ke total
    });

    // Total biaya pemeriksaan
    var totalBiaya = biayaDokter + totalBiayaObat;
    document.getElementById("biaya-periksa").value = totalBiaya;
}


</script>


</x-layout-dokter>
