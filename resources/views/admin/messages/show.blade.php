@extends('layouts.admin')

@section('title', 'Pesan dari '.$message->name)

@section('content')
    <a href="{{ route('admin.messages.index') }}"> Kembali ke pesan</a>
    <article class="message-detail">
        <span class="eyebrow">{{ $message->created_at->translatedFormat('d F Y H:i') }}</span>
        <h1>{{ $message->subject ?: 'Pesan dari '.$message->name }}</h1>
        <p><strong>{{ $message->name }}</strong><br><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>
        <div class="message-body">{{ $message->message }}</div>
        <div class="actions">
            <form method="post" action="{{ route('admin.messages.update', $message) }}">@csrf @method('put')<button class="button secondary">Tandai belum dibaca</button></form>
            <form method="post" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Hapus pesan ini?')">@csrf @method('delete')<button class="button">Hapus</button></form>
        </div>
    </article>
@endsection
