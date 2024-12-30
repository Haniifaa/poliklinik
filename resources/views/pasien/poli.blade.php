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
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Poli</th>
                        <th scope="col" class="px-6 py-3">Dokter</th>
                        <th scope="col" class="px-6 py-3">Hari</th>
                        <th scope="col" class="px-6 py-3">Mulai</th>
                        <th scope="col" class="px-6 py-3">Selesai</th>
                        <th scope="col" class="px-6 py-3">Antrian</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    @forelse($riwayat as $r)

                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $loop->iteration }}</th>
                        <td class="px-6 py-4">{{ $r->jadwal?->dokter?->poli?->nama_poli}}</td>
                        <td class="px-6 py-4">{{ $r->jadwal?->dokter?->nama ?? 'Tidak ada data' }}</td>
                        <td class="px-6 py-4">{{ $r->jadwal?->hari ?? 'Tidak ada data' }}</td>
                        <td class="px-6 py-4">{{ $r->jadwal?->jam_mulai ?? 'Tidak ada data' }}</td>
                        <td class="px-6 py-4">{{ $r->jadwal?->jam_selesai ?? 'Tidak ada data'}}</td>
                        <td class="px-6 py-4">{{ $r->no_antrian ?? 'Tidak ada data'}}</td>
                        <td class="px-6 py-4 w-40">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $r->periksa ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $r->periksa ? 'Sudah Diperiksa' : 'Belum Diperiksa' }}
                            </span>
                        </td>
                        <td class="flex items-center px-6 py-4">
                            <button id="openModal" class="font-medium text-blue-600 hover:underline bg-transparent border-none cursor-pointer">Detail Poli dan Riwayat</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center px-6 py-4">Tidak ada data untuk ditampilkan.</td>
                    </tr>

                    @endforelse

                </tbody>
            </table>
            <!-- Navigasi Pagination -->
<div class="mt-4">
    {{ $riwayat->links() }}
</div>

        </div>





    </div>


    </div>

<!-- Modal Structure -->
<div id="modalDetail" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg w-3/4 md:w-1/2">
      <!-- Modal Header -->
      <div class="flex justify-between items-center">
        <h3 class="text-xl font-semibold">Detail Riwayat</h3>
        <button id="closeModal" class="text-gray-600 hover:text-gray-900">&times;</button>
      </div>

      <!-- Modal Body (Tables and Information) -->
      <div class="mt-4">
        <!-- Tabel Riwayat -->
        <table class="min-w-full table-auto border-collapse">
          <thead>
            <tr>
              <th class="px-4 py-2 border">Nama Poli</th>
              <th class="px-4 py-2 border">Nama Dokter</th>
              <th class="px-4 py-2 border">Hari</th>
              <th class="px-4 py-2 border">Mulai</th>
              <th class="px-4 py-2 border">Selesai</th>
              <th class="px-4 py-2 border">Nomor Antrian</th>
            </tr>
          </thead>
          <tbody>
            @foreach($riwayat as $r)

                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $r->jadwal?->dokter?->poli?->nama_poli}}</td>
                        <td class="px-6 py-4">{{ $r->jadwal?->dokter?->nama ?? 'Tidak ada data' }}</td>
                        <td class="px-6 py-4">{{ $r->jadwal?->hari ?? 'Tidak ada data' }}</td>
                        <td class="px-6 py-4">{{ $r->jadwal?->jam_mulai ?? 'Tidak ada data' }}</td>
                        <td class="px-6 py-4">{{ $r->jadwal?->jam_selesai ?? 'Tidak ada data'}}</td>
                        <td class="px-6 py-4">
                            <span class="bg-green-400 text-white py-1 px-3 rounded-full text-sm">
                                {{ $r->no_antrian ?? 'Tidak ada data'}}
                            </span>
                        </td>
                                    </tr>

          </tbody>
        </table>

        <!-- Additional Details -->
        <div class="mt-6">
            <p><strong>Tanggal Periksa:</strong> {{ $r->periksa?->tgl_periksa ?? 'Belum ada tanggal periksa' }}</p>
            <p><strong>Catatan:</strong> {{ $r->periksa?->catatan ?? 'Tidak ada catatan' }}</p>

            <p><strong>Daftar Obat yang Diresepkan:</strong></p>
            {{-- <pre>{{ var_dump($r->periksa?->detailPeriksa?->toArray()) }}</pre> --}}

    @if ($r->periksa?->detailPeriksa)

        <ul>
            @foreach ($r->periksa->detailPeriksa as $detail)
            <li>
                {{ $detail->obat?->nama_obat ?? 'Nama obat tidak ditemukan' }} -
                {{ $detail->obat?->kemasan ?? 'Kemasan tidak ditemukan' }}
            </li>
        @endforeach
        </ul>
    @else
        <p>Tidak ada obat yang diresepkan</p>
    @endif

            <p class="mt-4">
                <strong class="text-xl font-semibold">Biaya Periksa:</strong>
                <span class="bg-purple-600 text-white px-4 py-2 rounded-full text-xl font-semibold">
                    {{ $r->periksa?->biaya_periksa ? 'Rp ' . number_format($r->periksa->biaya_periksa, 0, ',', '.') : 'Belum diperiksa' }}
                </span>
            </p>
        </div>

      </div>
      @endforeach


      <!-- Modal Footer -->
      <div class="mt-6 text-right">
        <button id="closeModalBtn" class="bg-blue-600 text-white px-4 py-2 rounded">Tutup</button>
      </div>
    </div>
  </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const poliSelect = document.getElementById('poli');
            const jadwalSelect = document.getElementById('jadwal');
            const jadwalOptions = jadwalSelect ? Array.from(jadwalSelect.options) : []; // Simpan semua opsi jadwal jika jadwalSelect ada

            if (poliSelect && jadwalSelect) {
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
            }

            const modal = document.getElementById('modalDetail');
            const openModalBtn = document.getElementById('openModal');
            const closeModalBtn = document.getElementById('closeModal');
            const closeModalButton = document.getElementById('closeModalBtn');

            if (modal && openModalBtn && closeModalBtn && closeModalButton) {
                // Open Modal Function
                openModalBtn.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent link from redirecting
                    modal.classList.remove('hidden');
                });

                // Close Modal Function
                closeModalBtn.addEventListener('click', function() {
                    modal.classList.add('hidden');
                });

                closeModalButton.addEventListener('click', function() {
                    modal.classList.add('hidden');
                });

                // Close modal when clicking outside of modal content
                window.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            }
        });
        </script>


</x-layout-pasien>
