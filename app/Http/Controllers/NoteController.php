<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::all();
        return view('index', compact('notes'));
    }

    public function store(Request $request)
    {
        $note = Note::create($request->all());
        return response()->json($note, 201);
    }

    public function show(Note $note)
    {
        return $note;
    }

    public function update(Request $request, Note $note)
    {
        $note->update($request->all());
        return response()->json($note, 200);
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return response()->json(null, 204);
    }
}
