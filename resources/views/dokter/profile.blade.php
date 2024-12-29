<x-layout-dokter>
    <div class="max-w-4xl mx-auto mt-10">
        @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex items-center justify-center mb-6">
            <div class="relative">
                <img src=""
                     alt="Foto Profil Dokter"
                     class="w-32 h-32 rounded-full shadow-md object-cover">
                <button
                    class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 transition duration-300"
                    onclick="document.getElementById('photo').click();">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
        </div>

        <form id="dokterForm" action="{{ route('dokter.update', ['dokter' => $dokter->id]) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')

            <input type="file" id="photo" name="photo" class="hidden" accept="image/*" disabled>
            @error('photo')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <div class="mb-4">
                <label for="nama" class="block text-gray-700 font-semibold">Nama:</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $dokter->nama) }}"
                    class="w-full mt-2 border-gray-300 rounded-md focus:ring focus:ring-blue-200" disabled>
                @error('nama')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="alamat" class="block text-gray-700 font-semibold">Alamat:</label>
                <input type="text" id="alamat" name="alamat" value="{{ old('alamat', $dokter->alamat) }}"
                    class="w-full mt-2 border-gray-300 rounded-md focus:ring focus:ring-blue-200" disabled>
                @error('alamat')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="no_hp" class="block text-gray-700 font-semibold">No HP:</label>
                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $dokter->no_hp) }}"
                    class="w-full mt-2 border-gray-300 rounded-md focus:ring focus:ring-blue-200" disabled>
                @error('no_hp')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="id_poli" class="block text-gray-700 font-semibold">Poli:</label>
                <select id="id_poli" name="id_poli" class="w-full mt-2 border-gray-300 rounded-md focus:ring focus:ring-blue-200" disabled>
                    <option value="" disabled>Pilih Poli</option>
                    @foreach ($poli as $item)
                        <option value="{{ $item->id }}" {{ $dokter->poli && $dokter->poli->id == $item->id ? 'selected' : '' }}>
                            {{ $item->nama_poli }}
                        </option>
                    @endforeach
                </select>
                @error('id_poli')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>





            <button type="button"
                id="editButton"
                class="w-full mb-4 bg-purple-400 text-white py-2 px-4 rounded-md hover:bg-purple-600 transition duration-300"
                onclick="enableFormFields()">
                Edit Profil
            </button>
            <button type="submit"
                id="saveButton"
                class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 hidden">
                Simpan
            </button>
        </form>
    </div>

    <script>
        function enableFormFields() {
            document.querySelectorAll('#dokterForm input, #dokterForm select').forEach(field => {
                field.disabled = false;
            });
            document.getElementById('saveButton').classList.remove('hidden');
            document.getElementById('editButton').classList.add('hidden');
        }
    </script>
</x-layout-dokter>
