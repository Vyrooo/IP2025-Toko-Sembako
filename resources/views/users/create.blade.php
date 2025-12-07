<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Pengguna</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                            <option value="kasir" @selected(old('role') == 'kasir')>Kasir</option>
                            <option value="owner" @selected(old('role') == 'owner')>Owner</option>
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Password</label>
                        <div class="input-group has-validation">
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            
                            

                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                            
                            
                        </div>
                    </div>

                </div>
                <div class="mt-4">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    
</x-app-layout>