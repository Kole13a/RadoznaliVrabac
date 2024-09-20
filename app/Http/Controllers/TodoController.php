<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        return Todo::latest()->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[A-Za-z0-9\s,.!?-]+$/'
            ],
            'description' => [
                'required',
                'string',
                'min:5',
                'max:255',
                'regex:/^[^<>]+$/'
            ]
        ], [
            'title.required' => 'Moras napisati naslov :)',
            'title.min' => 'Naslov mora imati najmanje 3 karaktera.',
            'title.max' => 'Naslov ne sme biti duži od 100 karaktera.',
            'title.regex' => 'Naslov može sadržavati samo slova, brojeve i osnovne znakove.',
            'description.required' => 'Moras napisati opis zadatka :)',
            'description.min' => 'Opis mora imati najmanje 5 karaktera.',
            'description.max' => 'Opis ne sme biti duži od 255 karaktera.',
            'description.regex' => 'Opis ne sme sadržavati te oznake.'
        ]);

        Todo::create($request->all());
    }

    public function show($id)
    {
        return Todo::findOrFail($id);
    }

    public function update(Request $request, $id)
    {

        if ($request->has('completed')) {
            $todo = Todo::findOrFail($id);
            $todo->update(['completed' => $request->completed]);
        }else{
        $this->validate($request, [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[A-Za-z0-9\s,.!?-]+$/'
            ],
            'description' => [
                'required',
                'string',
                'min:5',
                'max:255',
                'regex:/^[^<>]+$/'
            ]
        ], [
            'title.required' => 'Moras napisati naslov :)',
            'title.min' => 'Naslov mora imati najmanje 3 karaktera.',
            'title.max' => 'Naslov ne sme biti duži od 100 karaktera.',
            'title.regex' => 'Naslov može sadržavati samo slova, brojeve i osnovne znakove.',
            'description.required' => 'Moras napisati opis zadatka :)',
            'description.min' => 'Opis mora imati najmanje 5 karaktera.',
            'description.max' => 'Opis ne sme biti duži od 255 karaktera.',
            'description.regex' => 'Opis ne sme sadržavati te oznake.'
        ]);
        $todo = Todo::findOrFail($id);
        $todo->update($request->all());
    }
        $todo->save();
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();
        return Todo::latest()->get();
    }
}
