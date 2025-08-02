<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    function contactIndex() {
        $pageTitle = 'Contacts';
        $contacts  = Contact::searchable(['email'])->dateFilter()->orderBy('status')->latest()->paginate(getPaginate());

        return view('admin.page.contact', compact('pageTitle', 'contacts'));
    }

    function contactRemove($id) {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        $toast[] = ['success', 'The contact has been successfully deleted'];

        return back()->withToasts($toast);
    }

    function contactStatus($id) {
        return Contact::changeStatus($id);
    }
}
