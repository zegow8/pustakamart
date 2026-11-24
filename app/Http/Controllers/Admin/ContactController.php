<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

/**
 * Controller Manajemen Pesan Masuk (Admin)
 * Sesuai butir FR.IA.02: Menampung pesan dari fitur Contact to Admin.
 */
class ContactController extends Controller
{
    /**
     * Menampilkan daftar pesan masuk dari pelanggan/pengunjung dengan filter status:
     * semua, belum dibaca, sudah dibaca.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Contact::query();

        if ($status === 'unread') {
            $query->where('is_read', false);
        } elseif ($status === 'read') {
            $query->where('is_read', true);
        }

        $contacts = $query->latest()->paginate(10)->withQueryString();

        $allCount = Contact::count();
        $unreadCount = Contact::where('is_read', false)->count();
        $readCount = Contact::where('is_read', true)->count();

        return view('admin.contacts.index', compact('contacts', 'status', 'allCount', 'unreadCount', 'readCount'));
    }

    /**
     * Menandai pesan sebagai dibaca / belum dibaca (Toggle).
     */
    public function toggleRead($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->is_read = $contact->is_read ? 0 : 1;
        $contact->save();

        $statusText = $contact->is_read ? 'ditandai sudah dibaca' : 'ditandai belum dibaca';
        return back()->with('success', "Pesan dari {$contact->name} berhasil {$statusText}.");
    }

    /**
     * Menandai pesan sebagai telah dibaca.
     */
    public function markAsRead($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['is_read' => true]);

        return back()->with('success', 'Pesan dari ' . $contact->name . ' telah ditandai sebagai dibaca.');
    }

    /**
     * Menghapus pesan masuk.
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}
