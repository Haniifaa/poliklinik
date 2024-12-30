<x-layout-pasien>
    <div class="p-4  mt-14">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

        <div class="relative p-6 rounded-lg shadow-lg mb-8 z-10 bg-gradient-to-r from-white to-white">
            <!-- Circular Gradient Background (inside the box) -->
            <div class="absolute inset-0 rounded-lg bg-gradient-to-r from-white to-white overflow-hidden">
                <!-- Circular Gradients -->
                <div class="absolute -top-20 -left-20 w-96 h-96 bg-gradient-to-r from-white to-pink-500 rounded-full blur-3xl opacity-30"></div>
                <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full blur-3xl opacity-30"></div>

                <!-- Decorative Shapes -->
                <div class="absolute top-1/4 left-1/3 transform rotate-45 w-32 h-32 bg-purple-300/50 rounded-xl blur-xl"></div>
                <div class="absolute bottom-1/4 right-1/4 transform -rotate-45 w-40 h-40 bg-blue-300/50 rounded-full blur-lg"></div>
            </div>

            <!-- Greeting Content -->
            <div class="relative z-10">
                <h2 class="text-3xl font-semibold text-purple-500">Halo, {{ session('pasien')->nama }}!</h2>
                <p class="mt-2 text-md text-gray-600">Selamat datang kembali! Siap untuk mendaftar ke dokter dan mendapatkan layanan kesehatan terbaik? Ayo jelajahi dashboard Anda dan daftar untuk konsultasi hari ini.</p>
            </div>
        </div>


        <div class="p-4 mt-14">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Terakhir Pasien</h1>

            <!-- Tabel Riwayat -->
            <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Tanggal Periksa</th>
                            <th scope="col" class="px-6 py-3">Nama Dokter</th>
                            <th scope="col" class="px-6 py-3">Keluhan</th>
                            <th scope="col" class="px-6 py-3">Catatan</th>
                            <th scope="col" class="px-6 py-3">Obat</th>
                            <th scope="col" class="px-6 py-3">Biaya Periksa</th>
                            <th scope="col" class="px-6 py-3">Status</th>

                            </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $r)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900">{{ $loop->iteration }}</th>
                                <td class="px-6 py-4">{{ $r->tgl_periksa }}</td>
                                <td class="px-6 py-4">{{ $r->daftarPoli->dokter->nama }}</td>
                                <td class="px-6 py-4">{{ $r->daftarPoli->keluhan }}</td>
                                <td class="px-6 py-4">{{ $r->catatan }}</td>
                                <td class="px-6 py-4">    @foreach ($r->detailPeriksa as $detail)
                                    {{ $detail->obat->nama_obat ?? '-' }}@if (!$loop->last), @endif
                                @endforeach
                            </td>
                            <td class="px-6 py-4">Rp {{ number_format($r->biaya_periksa, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 w-40">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $r->status === 'sudah diperiksa' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $r->status }}
                                </span>
                            </td>


                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Tidak ada data pasien.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout-pasien>

