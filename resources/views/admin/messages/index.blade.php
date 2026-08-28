@extends('layouts.admin')

@section('title', 'Pesan masuk')

@section('content')
    <div class="heading">
        <h1>Pesan masuk</h1>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Pengirim</th><th>Subjek</th><th>Status</th><th>Tanggal</th></tr></thead>
            <tbody>
                @forelse ($messages as $message)
                    <tr>
                        <td><a href="{{ route('admin.messages.show', $message) }}">{{ $message->name }}</a><br><small>{{ $message->email }}</small></td>
                        <td>{{ $message->subject ?: 'Tanpa subjek' }}</td>
                        <td>{{ $message->read_at ? 'Dibaca' : 'Baru' }}</td>
                        <td>{{ $message->created_at->translatedFormat('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Belum ada pesan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $messages->links() }}
@endsection
