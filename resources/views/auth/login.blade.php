<x-guest-layout>
    <div class="mb-4">
    <img src="{{ asset('img/toko.png') }}" 
         alt="Foto Toko" 
         class="d-block mx-auto img-fluid rounded shadow-sm" 
         style="max-height: 120px; object-fit: cover;">
    </div>
    @if (session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="vstack gap-3">
        @csrf

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <div class="input-group has-validation">
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                
                

                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-primary">Masuk</button>
        </div>
    </form><br><br>

    <div class="mt-5 pt-3 border-top text-center text-muted small">
        <p class="mb-0">Jl. Cikuray III No. 350</p>
        <p class="mb-0">Semarang, Jawa Tengah</p>
        <p class="mb-0">Telp: 0831-0142-7002</p>
    </div>

    
</x-guest-layout>