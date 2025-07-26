<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $id = session('user.id');
        
        $notes = User::find($id)
            ->notes()
            ->whereNull('deleted_at')
            ->get()
            ->toArray();

        return view('home', ['notes' => $notes]);
    }

    public function newNote()
    {
        return view('new_note');
    }

    public function newNoteSubmit(Request $request)
    {
        $request->validate(
            [
                'text_title' => 'required|string|min:3|max:200',
                'text_note' => 'required|string|min:3|max:3000'
            ],
            [
                'text_title.required' => 'O titulo é obrigatorio',
                'text_title.min' => 'Deve ter no minino :min caracteres',
                'text_title.max' => 'Deve ter no maximo :max caracteres',
                'text_note.required' => 'A nota é obrigatorio',
                'text_note.min' => 'Deve ter no minino :min caracteres',
                'text_note.max' => 'Deve ter no maximo :max caracteres',
            ]
        );

        $id = session('user.id');
        Note::insert([ 
            'user_id' => $id,
            'title' => $request->get('text_title'),
            'text' => $request->get('text_note'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('home');
    }

    public function editNote(string $id)
    {
        $id = Operations::decryptId($id);
        $note = Note::find($id);
        return view('edit_note', ['note' => $note]);
    }

    public function editNoteSubmit(Request $request)
    {
        $request->validate(
            [
                'text_title' => 'required|string|min:3|max:200',
                'text_note' => 'required|string|min:3|max:3000',
                'note_id' => 'required|string'
            ],
            [
                'text_title.required' => 'O titulo é obrigatorio',
                'text_title.min' => 'Deve ter no minino :min caracteres',
                'text_title.max' => 'Deve ter no maximo :max caracteres',
                'text_note.required' => 'A nota é obrigatorio',
                'text_note.min' => 'Deve ter no minino :min caracteres',
                'text_note.max' => 'Deve ter no maximo :max caracteres',
                'note_id.required' => 'Valor não encontrado'
            ]
        );

        $id = Operations::decryptId($request->get('note_id'));
        $note = Note::find($id);

        $note->title = $request->get('text_title');
        $note->text = $request->get('text_note');
        $note->save();

        return redirect()->route('home');
    }

    public function deleteNote(string $id)
    {
        $id = Operations::decryptId($id);
        $note = Note::find($id);

        return view('delete_note', ['note' => $note]);
    }

    public function deleteNoteConfirma(string $id)
    {
        $id = Operations::decryptId($id);
        
        $note = Note::find($id);
        $note->deleted_at = now();
        $note->save();

        return redirect()->route('home');
    }
}
