@extends('layouts.user')
@section('title', 'Notifikasi Saya')
@section('content')
<div style="max-width: 850px; margin: 0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin: 0 0 4px;">🔔 Notifikasi Saya</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Pembaruan status pesanan, pengiriman resi, dan balasan LiveChat admin.</p>
        </div>
        @if($unreadCount > 0)
            <form action="/user/notifications/read-all" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm" style="font-weight:700;">
                    ✓ Tandai Semua Sudah Dibaca ({{ $unreadCount }})
                </button>
            </form>
        @endif
    </div>

    <div class="panel" style="padding:0; overflow:hidden;">
        @forelse($notifications as $n)
            <a href="/user/notifications/{{ $n->id }}/read"
               style="display:flex; gap:16px; align-items:flex-start; padding:18px 22px; border-bottom:1px solid var(--border); text-decoration:none; background: {{ $n->is_read ? 'white' : '#fff5f5' }}; transition:background 0.15s;">
                <div style="width:42px; height:42px; border-radius:50%; background: {{ $n->is_read ? '#f1f5f9' : '#fee2e2' }}; color: {{ $n->is_read ? '#64748b' : '#800000' }}; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0;">
                    {{ $n->is_read ? '📩' : '🔔' }}
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:4px; flex-wrap:wrap;">
                        <strong style="font-size:0.95rem; color: {{ $n->is_read ? 'var(--text-main)' : '#800000' }};">
                            {{ $n->title }}
                        </strong>
                        <span style="font-size:0.75rem; color:var(--text-muted);">
                            {{ $n->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p style="margin:0; font-size:0.88rem; color:#475569; line-height:1.5;">
                        {{ $n->message }}
                    </p>
                </div>
                @if(!$n->is_read)
                    <span style="width:10px; height:10px; border-radius:50%; background:#dc2626; margin-top:6px; flex-shrink:0;"></span>
                @endif
            </a>
        @empty
            <div style="padding:48px 20px; text-align:center; color:var(--text-muted);">
                <div style="font-size:3rem; margin-bottom:10px;">🔕</div>
                <h3 style="margin:0 0 6px; font-size:1.1rem; color:var(--text-main);">Belum Ada Notifikasi</h3>
                <p style="margin:0; font-size:0.85rem;">Semua pemberitahuan aktivitas pesanan dan chat akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 20px;">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
