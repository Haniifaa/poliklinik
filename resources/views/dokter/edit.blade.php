<x-layout-dokter>
    <div class="max-w-4xl mx-auto mt-10">
        <form action="{{ route('dokter.update') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf

            <div class="mb-4">
                <label for="nama" class="block text-gray-700">Nama:</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $dokter->nama) }}"
                    class="w-full mt-2 border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                @error('nama')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="alamat" class="block text-gray-700">Alamat:</label>
                <input type="text" id="alamat" name="alamat" value="{{ old('alamat', $dokter->alamat) }}"
                    class="w-full mt-2 border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                @error('alamat')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="no_hp" class="block text-gray-700">No HP:</label>
                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $dokter->no_hp) }}"
                    class="w-full mt-2 border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                @error('no_hp')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="id_poli" class="block text-gray-700">Poli:</label>
                <select id="id_poli" name="id_poli" class="w-full mt-2 border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                    @foreach ($poli as $item)
                    <option value="{{ $item->id }}" @if ($dokter->id_poli == $item->id) selected @endif>
                        {{ $item->nama }}
                    </option>
                    @endforeach
                </select>
                @error('id_poli')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                Simpan
            </button>
        </form>
    </div>

</x-layout-dokter>
