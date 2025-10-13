@extends('layouts.app')

@section('content')
<div class="bg-[#0f0f0f] min-h-screen text-white py-12 px-4 md:px-8">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold mb-8 text-center text-[#FEA923]">Akun Saya</h2>

        <!-- Tabs -->
        <div class="flex border-b border-gray-700 mb-6 justify-center">
            <button id="tab-profile" class="tab-btn px-6 py-3 font-semibold text-[#FEA923] border-b-2 border-[#FEA923]">Profil</button>
            <button id="tab-password" class="tab-btn px-6 py-3 font-semibold text-gray-400 hover:text-[#FEA923] transition">Ubah Password</button>
        </div>

        <!-- Profile Form -->
        <div id="profile-form" class="bg-[#1a1a1a] rounded-xl p-8 shadow-lg border border-gray-700">
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-gray-400 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                        class="w-full px-4 py-2 rounded-lg bg-[#141414] border border-gray-600 text-white focus:outline-none focus:border-[#FEA923]">
                </div>

                <div>
                    <label class="block text-gray-400 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                        class="w-full px-4 py-2 rounded-lg bg-[#141414] border border-gray-600 text-white focus:outline-none focus:border-[#FEA923]">
                </div>

                <div>
                    <label class="block text-gray-400 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-2 rounded-lg bg-[#141414] border border-gray-600 text-white focus:outline-none focus:border-[#FEA923]">
                </div>

                <div>
                    <label class="block text-gray-400 mb-1">Nomor Telepon</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}"
                        class="w-full px-4 py-2 rounded-lg bg-[#141414] border border-gray-600 text-white focus:outline-none focus:border-[#FEA923]">
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#FEA923] to-[#ff8800] py-2.5 rounded-lg font-semibold hover:opacity-90 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Form -->
        <div id="password-form" class="hidden bg-[#1a1a1a] rounded-xl p-8 shadow-lg border border-gray-700">
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                <div>
                    <label class="block text-gray-400 mb-1">Password Lama</label>
                    <input type="password" name="current_password"
                        class="w-full px-4 py-2 rounded-lg bg-[#141414] border border-gray-600 text-white focus:outline-none focus:border-[#FEA923]">
                </div>

                <div>
                    <label class="block text-gray-400 mb-1">Password Baru</label>
                    <input type="password" name="new_password"
                        class="w-full px-4 py-2 rounded-lg bg-[#141414] border border-gray-600 text-white focus:outline-none focus:border-[#FEA923]">
                </div>

                <div>
                    <label class="block text-gray-400 mb-1">Konfirmasi Password Baru</label>
                 <input type="password" name="new_password_confirmation"
                        class="w-full px-4 py-2 rounded-lg bg-[#141414] border border-gray-600 text-white focus:outline-none focus:border-[#FEA923]">
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#FEA923] to-[#ff8800] py-2.5 rounded-lg font-semibold hover:opacity-90 transition">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const tabProfile = document.getElementById('tab-profile');
    const tabPassword = document.getElementById('tab-password');
    const profileForm = document.getElementById('profile-form');
    const passwordForm = document.getElementById('password-form');

    tabProfile.addEventListener('click', () => {
        tabProfile.classList.add('text-[#FEA923]', 'border-b-2', 'border-[#FEA923]');
        tabPassword.classList.remove('text-[#FEA923]', 'border-b-2', 'border-[#FEA923]');
        tabPassword.classList.add('text-gray-400');
        profileForm.classList.remove('hidden');
        passwordForm.classList.add('hidden');
    });

    tabPassword.addEventListener('click', () => {
        tabPassword.classList.add('text-[#FEA923]', 'border-b-2', 'border-[#FEA923]');
        tabProfile.classList.remove('text-[#FEA923]', 'border-b-2', 'border-[#FEA923]');
        tabProfile.classList.add('text-gray-400');
        passwordForm.classList.remove('hidden');
        profileForm.classList.add('hidden');
    });
</script>
@endsection
