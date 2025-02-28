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
        return response()->json([
            'id' => $note->id,
            'content' => $note->content,
            'created_at' => $note->created_at->format('d.m.Y H:i'),
            'updated_at' => $note->updated_at->format('d.m.Y H:i')
        ]);
    }

    public function update(Request $request, Note $note)
    {
        $note->update($request->only('content'));
        return response()->json($note, 200);
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return response()->json(null, 204);
    }
}
