@extends('layouts.admin')

@section('title', 'Edit Film')
@section('page-title', 'Edit Film')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.films.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Film
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
        <form action="{{ route('admin.films.update', $film->film_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Judul Film -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Film <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul" value="{{ old('judul', $film->judul) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('judul') border-red-500 @enderror"
                    placeholder="Contoh: Avengers: Endgame">
                @error('judul')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sinopsis -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Sinopsis <span class="text-red-500">*</span>
                </label>
                <textarea name="sinopsis" rows="5" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sinopsis') border-red-500 @enderror"
                    placeholder="Tulis sinopsis film...">{{ old('sinopsis', $film->sinopsis) }}</textarea>
                @error('sinopsis')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Poster Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Poster Film
                </label>
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img id="posterPreview" src="{{ asset($film->poster_url) }}" 
                            class="h-40 w-28 object-cover rounded-lg border-2 border-gray-300">
                    </div>
                    <div class="flex-1">
                        <input type="file" name="poster" id="posterInput" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('poster') border-red-500 @enderror"
                            onchange="previewPoster(event)">
                        <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, GIF, WEBP. Max: 2MB</p>
                        <p class="text-xs text-blue-600 mt-1">Kosongkan jika tidak ingin mengganti poster</p>
                        @error('poster')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Grid: Durasi, Rating, Genre, Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Durasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Durasi (Menit) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="durasi_menit" value="{{ old('durasi_menit', $film->durasi_menit) }}" min="30" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('durasi_menit') border-red-500 @enderror"
                        placeholder="Contoh: 120">
                    @error('durasi_menit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Rating Usia <span class="text-red-500">*</span>
                    </label>
                    <select name="rating" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('rating') border-red-500 @enderror">
                        <option value="">Pilih Rating</option>
                        <option value="SU" {{ old('rating', $film->rating) == 'SU' ? 'selected' : '' }}>SU (Semua Umur)</option>
                        <option value="13+" {{ old('rating', $film->rating) == '13+' ? 'selected' : '' }}>13+</option>
                        <option value="17+" {{ old('rating', $film->rating) == '17+' ? 'selected' : '' }}>17+</option>
                        <option value="21+" {{ old('rating', $film->rating) == '21+' ? 'selected' : '' }}>21+</option>
                    </select>
                    @error('rating')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Genre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Genre <span class="text-red-500">*</span>
                    </label>
                    <select name="genre" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('genre') border-red-500 @enderror">
                        <option value="">Pilih Genre</option>
                        <option value="Action" {{ old('genre', $film->genre) == 'Action' ? 'selected' : '' }}>Action</option>
                        <option value="Adventure" {{ old('genre', $film->genre) == 'Adventure' ? 'selected' : '' }}>Adventure</option>
                        <option value="Comedy" {{ old('genre', $film->genre) == 'Comedy' ? 'selected' : '' }}>Comedy</option>
                        <option value="Drama" {{ old('genre', $film->genre) == 'Drama' ? 'selected' : '' }}>Drama</option>
                        <option value="Horror" {{ old('genre', $film->genre) == 'Horror' ? 'selected' : '' }}>Horror</option>
                        <option value="Romance" {{ old('genre', $film->genre) == 'Romance' ? 'selected' : '' }}>Romance</option>
                        <option value="Sci-Fi" {{ old('genre', $film->genre) == 'Sci-Fi' ? 'selected' : '' }}>Sci-Fi</option>
                        <option value="Thriller" {{ old('genre', $film->genre) == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                        <option value="Animation" {{ old('genre', $film->genre) == 'Animation' ? 'selected' : '' }}>Animation</option>
                    </select>
                    @error('genre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Tayang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Tayang <span class="text-red-500">*</span>
                    </label>
                    <select name="status_tayang" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status_tayang') border-red-500 @enderror">
                        <option value="">Pilih Status</option>
                        <option value="Playing Now" {{ old('status_tayang', $film->status_tayang) == 'Playing Now' ? 'selected' : '' }}>Playing Now</option>
                        <option value="Upcoming" {{ old('status_tayang', $film->status_tayang) == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="Selesai" {{ old('status_tayang', $film->status_tayang) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status_tayang')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Trailer URL -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Trailer URL (YouTube)
                </label>
                <input type="url" name="trailer_url" value="{{ old('trailer_url', $film->trailer_url) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('trailer_url') border-red-500 @enderror"
                    placeholder="https://www.youtube.com/watch?v=...">
                @error('trailer_url')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid: Produser, Sutradara, Penulis, Produksi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Produser</label>
                    <input type="text" name="produser" value="{{ old('produser', $film->produser) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Nama produser">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sutradara</label>
                    <input type="text" name="sutradara" value="{{ old('sutradara', $film->sutradara) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Nama sutradara">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penulis</label>
                    <input type="text" name="penulis" value="{{ old('penulis', $film->penulis) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Nama penulis">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Produksi</label>
                    <input type="text" name="produksi" value="{{ old('produksi', $film->produksi) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Studio produksi">
                </div>
            </div>

            <!-- Cast List -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Daftar Pemeran (Cast)
                </label>
                <textarea name="cast_list" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Pisahkan dengan koma. Contoh: Robert Downey Jr., Chris Evans, Scarlett Johansson">{{ old('cast_list', $film->cast_list) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Pisahkan nama aktor dengan koma (,)</p>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.films.index') }}" 
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>
                    Update Film
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewPoster(event) {
        const input = event.target;
        const preview = document.getElementById('posterPreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush