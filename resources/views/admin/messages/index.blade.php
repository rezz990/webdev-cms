@extends('layouts.admin')

@section('title', 'Pesan masuk')

@section('content')
    <header class="content-index-header">
        <div><span class="eyebrow">Inbox</span><h1>Pesan masuk</h1><p>Cari, baca, dan kelola percakapan dari pengunjung website.</p></div>
    </header>

    <form class="content-filters" method="get">
        <label><span class="sr-only">Cari pesan</span><input name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau subjek"></label>
        <label><span class="sr-only">Filter status</span><select name="status"><option value="">Semua pesan</option><option value="unread" @selected(request('status') === 'unread')>Belum dibaca</option><option value="read" @selected(request('status') === 'read')>Sudah dibaca</option></select></label>
        <button type="submit">Terapkan filter</button>
        @if(request()->hasAny(['q', 'status']))<a href="{{ route('admin.messages.index') }}">Reset</a>@endif
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Pengirim</th><th>Subjek</th><th>Status</th><th>Tanggal</th><th><span class="sr-only">Aksi</span></th></tr></thead>
            <tbody>
                @forelse ($messages as $message)
                    <tr>
                        <td><strong>{{ $message->name }}</strong><small class="table-subtitle">{{ $message->email }}</small></td>
                        <td>{{ $message->subject ?: 'Tanpa subjek' }}</td>
                        <td><span class="status-badge {{ $message->read_at ? '' : 'scheduled' }}">{{ $message->read_at ? 'Dibaca' : 'Baru' }}</span></td>
                        <td>{{ $message->created_at->translatedFormat('d M Y H:i') }}</td>
                        <td class="table-actions"><a href="{{ route('admin.messages.show', $message) }}">Buka</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty">Belum ada pesan sesuai filter.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $messages->links() }}
@endsection
