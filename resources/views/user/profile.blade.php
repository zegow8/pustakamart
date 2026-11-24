@extends('layouts.user')

@section('title', 'Pengaturan Profil')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: #3B0B0B; margin: 0 0 6px 0;">Pengaturan Akun</h1>
    <p class="text-muted" style="margin: 0; font-size: 0.9rem;">Kelola informasi profil pribadi dan keamanan kata sandi akun PustakaMart Anda.</p>
</div>

<div class="profile-grid-2col" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 340px), 1fr)); gap: 24px; align-items: start; max-width: 1100px;">
    <!-- KARTU KIRI: INFORMASI PROFIL -->
    <div class="panel" style="background: white; border: 1px solid #F5D5D5; border-radius: 16px; padding: 26px; box-shadow: 0 4px 15px rgba(128,0,0,0.03);">
        <h2 class="panel-title mb-4" style="font-size: 1.15rem; font-weight: 800; color: #3B0B0B; display: flex; align-items: center; gap: 8px;">
            <span>👤 Informasi Profil</span>
        </h2>
        <form action="/user/profile" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Nama Lengkap <span style="color:var(--danger);">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required placeholder="Nama Lengkap">
                @error('name') <small style="color:var(--danger);">{{ $message }}</small> @enderror
            </div>
            
            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Email (Tidak dapat diubah)</label>
                <input type="email" class="form-control" value="{{ $user->email }}" disabled style="background: #FFF5F5; color: #785858; cursor: not-allowed;">
            </div>

            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Nomor HP / WhatsApp</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890">
                @error('phone') <small style="color:var(--danger);">{{ $message }}</small> @enderror
            </div>

            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">NIK (KTP)</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik', $user->nik) }}" placeholder="16 digit NIK">
                @error('nik') <small style="color:var(--danger);">{{ $message }}</small> @enderror
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Alamat Pengiriman Lengkap</label>
                <textarea name="address" class="form-control" rows="3" placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten...">{{ old('address', $user->address) }}</textarea>
                @error('address') <small style="color:var(--danger);">{{ $message }}</small> @enderror
            </div>
            
            <button type="submit" class="btn btn-primary" style="font-weight: 700; padding: 11px 24px; border-radius: 8px;">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <!-- KARTU KANAN: GANTI PASSWORD -->
    <div class="panel" style="background: white; border: 1px solid #F5D5D5; border-radius: 16px; padding: 26px; box-shadow: 0 4px 15px rgba(128,0,0,0.03);">
        <h2 class="panel-title mb-4" style="font-size: 1.15rem; font-weight: 800; color: #3B0B0B; display: flex; align-items: center; gap: 8px;">
            <span>🔒 Ganti Password</span>
        </h2>
        <form action="/user/profile/password" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Password Saat Ini <span style="color:var(--danger);">*</span></label>
                <input type="password" name="current_password" class="form-control" required placeholder="Masukkan kata sandi lama">
                @error('current_password') <small style="color:var(--danger);">{{ $message }}</small> @enderror
            </div>

            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Password Baru <span style="color:var(--danger);">*</span></label>
                <input type="password" name="new_password" class="form-control" required minlength="6" placeholder="Minimal 6 karakter">
                @error('new_password') <small style="color:var(--danger);">{{ $message }}</small> @enderror
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Konfirmasi Password Baru <span style="color:var(--danger);">*</span></label>
                <input type="password" name="new_password_confirmation" class="form-control" required minlength="6" placeholder="Ulangi kata sandi baru">
                @error('new_password_confirmation') <small style="color:var(--danger);">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="font-weight: 700; padding: 11px 24px; border-radius: 8px;">
                Ubah Password
            </button>
        </form>
    </div>
</div>
@endsection
