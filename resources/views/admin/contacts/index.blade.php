@extends('layouts.admin')

@section('title', 'Pesan Kontak Masuk')

@section('content')
<div class="page-header flex-between mb-4" style="flex-wrap: wrap; gap: 14px;">
    <div>
        <h1 class="page-title">Pesan Kontak Masuk</h1>
        <p class="text-muted" style="margin: 4px 0 0;">Daftar pesan dan formulir yang dikirimkan oleh pengunjung/tamu dari halaman Hubungi Kami.</p>
    </div>
</div>

<!-- ============ 3 TABS STATUS: SEMUA, BELUM DIBACA, SUDAH DIBACA ============ -->
<div style="display: flex; gap: 10px; margin-bottom: 22px; flex-wrap: wrap; border-bottom: 2px solid var(--border); padding-bottom: 2px;">
    <a href="/admin/contacts?status=all" 
       style="padding: 10px 18px; font-size: 0.88rem; font-weight: 700; text-decoration: none; border-bottom: 3px solid {{ $status === 'all' ? 'var(--primary)' : 'transparent' }}; color: {{ $status === 'all' ? 'var(--primary)' : 'var(--text-muted)' }}; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px;">
        <span>Semua Pesan</span>
        <span style="font-size: 0.75rem; padding: 2px 8px; border-radius: 999px; background: {{ $status === 'all' ? '#FFD4D4' : '#F1F5F9' }}; color: {{ $status === 'all' ? '#800000' : '#64748B' }}; font-weight: 800;">
            {{ $allCount }}
        </span>
    </a>

    <a href="/admin/contacts?status=unread" 
       style="padding: 10px 18px; font-size: 0.88rem; font-weight: 700; text-decoration: none; border-bottom: 3px solid {{ $status === 'unread' ? 'var(--primary)' : 'transparent' }}; color: {{ $status === 'unread' ? 'var(--primary)' : 'var(--text-muted)' }}; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px;">
        <span>📬 Belum Dibaca</span>
        <span style="font-size: 0.75rem; padding: 2px 8px; border-radius: 999px; background: {{ $status === 'unread' ? '#800000' : '#FEE2E2' }}; color: {{ $status === 'unread' ? 'white' : '#991B1B' }}; font-weight: 800;">
            {{ $unreadCount }}
        </span>
    </a>

    <a href="/admin/contacts?status=read" 
       style="padding: 10px 18px; font-size: 0.88rem; font-weight: 700; text-decoration: none; border-bottom: 3px solid {{ $status === 'read' ? 'var(--primary)' : 'transparent' }}; color: {{ $status === 'read' ? 'var(--primary)' : 'var(--text-muted)' }}; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px;">
        <span>✓ Sudah Dibaca</span>
        <span style="font-size: 0.75rem; padding: 2px 8px; border-radius: 999px; background: {{ $status === 'read' ? '#0D8050' : '#DCFCE7' }}; color: {{ $status === 'read' ? 'white' : '#166534' }}; font-weight: 800;">
            {{ $readCount }}
        </span>
    </a>
</div>

<!-- ============ DAFTAR KARTU PESAN ============ -->
<div class="panel" style="padding: 0; overflow: hidden; background: white; border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    @forelse($contacts as $msg)
        <div style="padding: 22px 26px; border-bottom: 1px solid var(--border); background: {{ $msg->is_read ? 'white' : '#FFF9F9' }}; transition: background 0.15s;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; gap: 14px; align-items: flex-start;">
                    <div style="width: 46px; height: 46px; border-radius: 50%; background: {{ $msg->is_read ? '#F1F5F9' : '#FFD4D4' }}; color: {{ $msg->is_read ? '#64748B' : '#800000' }}; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.15rem; flex-shrink: 0;">
                        {{ strtoupper(substr($msg->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <strong style="font-size: 1rem; color: var(--text-main);">{{ $msg->name }}</strong>
                            <span style="font-size: 0.82rem; color: var(--text-muted); font-family: monospace;">&lt;{{ $msg->email }}&gt;</span>
                            @if(!$msg->is_read)
                                <span style="background: #800000; color: white; font-size: 0.65rem; font-weight: 800; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                    Belum Dibaca
                                </span>
                            @else
                                <span style="background: #E6F4EA; color: #0D8050; font-size: 0.65rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                    Sudah Dibaca
                                </span>
                            @endif
                        </div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 3px;">
                            Dikirim pada: {{ $msg->created_at->format('d M Y, H:i') }} WIB ({{ $msg->created_at->diffForHumans() }})
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 8px; align-items: center;">
                    <!-- FORM TOGGLE BACA / BELUM BACA -->
                    <form action="{{ route('admin.contacts.toggle', $msg->id) }}" method="POST">
                        @csrf
                        @if(!$msg->is_read)
                            <button type="submit" class="btn btn-outline btn-sm" style="font-weight: 700; color: #0D8050; border-color: #A7F3D0; background: #F0FDF4;" title="Tandai Sudah Dibaca">
                                ✓ Tandai Sudah Dibaca
                            </button>
                        @else
                            <button type="submit" class="btn btn-outline btn-sm" style="font-weight: 700; color: #800000; border-color: #F5B8B8; background: #FFF5F5;" title="Tandai Belum Dibaca">
                                ↺ Tandai Belum Dibaca
                            </button>
                        @endif
                    </form>

                    <!-- FORM HAPUS -->
                    <form action="{{ route('admin.contacts.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Hapus pesan dari {{ $msg->name }}?')">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" style="font-weight: 700;">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            <div style="margin-top: 14px; padding: 16px 20px; background: {{ $msg->is_read ? '#F8FAFC' : '#FFF2F2' }}; border: 1px solid {{ $msg->is_read ? '#E2E8F0' : '#FADBD8' }}; border-radius: 12px;">
                <div style="font-size: 0.88rem; font-weight: 800; color: #3B0B0B; margin-bottom: 6px;">
                    Subjek: {{ $msg->subject }}
                </div>
                <div style="font-size: 0.9rem; color: #4A2828; line-height: 1.6; white-space: pre-line;">{{ $msg->message }}</div>
            </div>
        </div>
    @empty
        <div style="padding: 56px 20px; text-align: center; color: var(--text-muted);">
            <div style="font-size: 3rem; margin-bottom: 12px;">📭</div>
            <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">
                @if($status === 'unread')
                    Tidak Ada Pesan Belum Dibaca
                @elseif($status === 'read')
                    Belum Ada Pesan yang Sudah Dibaca
                @else
                    Kotak Pesan Masuk Kosong
                @endif
            </h3>
            <p style="font-size: 0.88rem; max-width: 360px; margin: 0 auto; color: var(--text-muted);">
                @if($status === 'unread')
                    Semua pesan masuk telah Anda tandai sebagai dibaca.
                @elseif($status === 'read')
                    Pesan yang telah dibaca akan tampil di tab ini.
                @else
                    Belum ada pesan yang dikirim oleh pengunjung melalui formulir Hubungi Kami.
                @endif
            </p>
        </div>
    @endforelse

    @if($contacts->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid var(--border); display: flex; justify-content: center;">
            {{ $contacts->links() }}
        </div>
    @endif
</div>
@endsection
