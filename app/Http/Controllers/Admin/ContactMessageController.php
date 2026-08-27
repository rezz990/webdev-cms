<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View
    {
        return view('admin.messages.index', [
            'messages' => ContactMessage::query()->latest()->paginate(20),
        ]);
    }

    public function show(ContactMessage $message): View
    {
        if ($message->read_at === null) {
            $message->update(['read_at' => now()]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function update(ContactMessage $message): RedirectResponse
    {
        $message->update(['read_at' => $message->read_at === null ? now() : null]);

        return back()->with('success', 'Status pesan diperbarui.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Pesan dihapus.');
    }
}
