<!doctype html>
<html lang="id">
<body>
    <h1>Pesan baru dari website</h1>
    <p><strong>Nama:</strong> {{ $contactMessage->name }}</p>
    <p><strong>Email:</strong> {{ $contactMessage->email }}</p>
    <p><strong>Subjek:</strong> {{ $contactMessage->subject ?: 'Tanpa subjek' }}</p>
    <hr>
    <p style="white-space: pre-wrap">{{ $contactMessage->message }}</p>
</body>
</html>
