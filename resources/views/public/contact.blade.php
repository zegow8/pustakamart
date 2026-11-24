@extends('layouts.app')

@section('title', 'Hubungi Kami - PustakaMart')

@section('content')
<div style="max-width: 760px; margin: 40px auto; padding: 0 16px;">

    <div style="text-align: center; margin-bottom: 32px;">
        <span style="display: inline-block; padding: 4px 14px; background: #FFD4D4; color: #800000; font-size: 0.75rem; font-weight: 800; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">
            Customer Care PustakaMart
        </span>
        <h1 style="font-size: 2rem; font-weight: 800; color: #800000; margin-bottom: 8px;">Hubungi Kami</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 560px; margin: 0 auto; line-height: 1.5;">
            Punya pertanyaan mengenai stok buku, informasi pengiriman pesanan, atau permohonan pemulihan akun (lupa password)? Kami siap membantu Anda.
        </p>
    </div>

    @auth
        <!-- KARTU KHUSUS USER YANG SUDAH LOGIN: LIVECHAT PRIORITAS -->
        <div class="panel" style="background: white; border: 2px solid #800000; border-radius: 16px; padding: 28px; box-shadow: 0 10px 25px rgba(128,0,0,0.06); margin-bottom: 24px; text-align: center;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: #FFD4D4; color: #800000; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin: 0 auto 16px auto;">
                💬
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #800000; margin-bottom: 8px;">
                Halo, {{ auth()->user()->name }}!
            </h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); max-width: 480px; margin: 0 auto 20px auto; line-height: 1.5;">
                Karena Anda sudah terdaftar dan masuk ke akun PustakaMart, Anda dapat langsung berbicara dengan Customer Service kami secara <strong>real-time</strong> melalui fitur <strong>LiveChat</strong>.
            </p>
            <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                <a href="{{ auth()->user()->role === 'admin' ? '/admin/chats' : '/user/chat' }}" 
                   class="btn btn-primary" 
                   style="font-weight: 800; padding: 12px 28px; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px;">
                    <span>Buka LiveChat Customer Service</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <div style="text-align: center; margin: 24px 0 16px 0;">
            <span style="font-size: 0.85rem; color: var(--text-muted); background: var(--bg-main); padding: 0 12px;">
                atau ingin meninggalkan tiket pesan tertulis?
            </span>
        </div>
    @endauth

    <!-- FORMULIR KONTAK (UNTUK GUEST ATAU TIKET TERTULIS) -->
    <div class="panel" style="background: white; border: 1px solid var(--border); border-radius: 16px; padding: 28px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <form action="{{ route('contact.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Nama Lengkap <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="name" class="form-control" required 
                           value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" 
                           placeholder="Contoh: Lyta Sayang">
                    @error('name') <small style="color:var(--danger);">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Alamat Email <span style="color:var(--danger);">*</span></label>
                    <input type="email" name="email" class="form-control" required 
                           value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" 
                           placeholder="nama@email.com">
                    @error('email') <small style="color:var(--danger);">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Topik / Subjek Pesan <span style="color:var(--danger);">*</span></label>
                <input type="text" name="subject" class="form-control" required 
                       value="{{ old('subject') }}" 
                       placeholder="Contoh: Tanya Ketersediaan Stok Buku Novel">
                @error('subject') <small style="color:var(--danger);">{{ $message }}</small> @enderror
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Isi Pesan Pertanyaan <span style="color:var(--danger);">*</span></label>
                <textarea name="message" class="form-control" rows="5" required 
                          placeholder="Tuliskan rincian pertanyaan Anda dengan jelas di sini... (Khusus permohonan reset password, cantumkan NIK KTP dan No. HP Anda demi verifikasi keamanan)">{{ old('message') }}</textarea>
                @error('message') <small style="color:var(--danger);">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="font-weight: 800; padding: 13px; font-size: 0.95rem;">
                Kirim Pesan ke Admin &rarr;
            </button>
        </form>
    </div>

</div>
@endsection
