<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <button
                    data-drawer-target="logo-sidebar"
                    data-drawer-toggle="logo-sidebar"
                    aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                >
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a href="/" class="flex ms-2 md:me-24">
                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-200">
                        Poliklinik
                    </span>
                </a>

            </div>
            <div class="flex items-center">
                <div class="flex items-center ms-3">
                    <a href="{{ route('dokter.edit') }}"
            class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300">
            <span class="sr-only">Edit Profile</span>
            <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg"
                alt="user photo">
        </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<aside
id="sidebar"
class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0"
aria-label="Sidebar"
>
<div class="h-full px-3 pb-4 overflow-y-auto bg-white">
    <ul class="space-y-2 font-medium">
        <li>
            <a href="{{ route('dokter.dashboard') }}" class="flex items-center p-2 text-purple-800 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-purple-800 transition duration-75 group-hover:text-purple-700" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                    <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                    <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                </svg>
                <span class="ms-3">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dokter.jadwal-periksa') }}" class="flex items-center p-2 text-purple-800 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-purple-800 transition duration-75 group-hover:text-purple-700" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                    <path d="M2 5a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V5Zm15.5 1h-3a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5ZM6.5 6h3a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 1 .5-.5ZM6 12.5c0-.276.224-.5.5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5Z"/>
                </svg>
                <span class="ms-3">Jadwal Periksa</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dokter.periksa-pasien') }}" class="flex items-center p-2 text-purple-800 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-purple-800 transition duration-75 group-hover:text-purple-700" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 22">
                    <path d="M11 0a11 11 0 1 0 11 11A11.013 11.013 0 0 0 11 0Zm5 12h-4v4a1 1 0 0 1-2 0v-4H6a1 1 0 0 1 0-2h4V6a1 1 0 0 1 2 0v4h4a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="ms-3">Memeriksa Pasien</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dokter.riwayat-pasien') }}" class="flex items-center p-2 text-purple-800 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-purple-800 transition duration-75 group-hover:text-purple-700" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 22">
                    <path d="M4 2h14a2 2 0 0 1 2 2v16l-8-4-8 4V4a2 2 0 0 1 2-2Zm3 6a2 2 0 1 0 2-2 2 2 0 0 0-2 2Zm1 2a4 4 0 0 0-4 4 .75.75 0 0 0 .75.75h6.5a.75.75 0 0 0 .75-.75 4 4 0 0 0-4-4Z"/>
                </svg>
                <span class="ms-3">Riwayat Pasien</span>
            </a>
        </li>
    </ul>
</div>

</aside>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.querySelector('[data-drawer-toggle="logo-sidebar"]');
    const sidebar = document.getElementById('sidebar');

    // Tambahkan event listener ke tombol
    toggleButton.addEventListener('click', () => {
        if (sidebar.classList.contains('-translate-x-full')) {
            // Tampilkan sidebar
            sidebar.classList.remove('-translate-x-full');
        } else {
            // Sembunyikan sidebar
            sidebar.classList.add('-translate-x-full');
        }
    });
});
</script>