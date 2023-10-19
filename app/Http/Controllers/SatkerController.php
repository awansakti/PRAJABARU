<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
use App\Http\Middleware\RedirectIfNotParmitted;
use App\Models\Satker;
use Illuminate\Support\Facades\Redirect;

class SatkerController extends Controller
{
    // public function __construct(){
    //     $this->middleware(RedirectIfNotParmitted::class.':satker');
    // }

    public function index()
    {
        return Inertia::render('Satker/Index',[
            'title' => 'Satuan Kerja',
            'filters' => Request::all('search'),
            'satker' => Satker::orderBy('nama_satker')
                    ->paginate(10)
                    ->withQueryString()
                    ->through(function($satker){
                        return [
                            'id' => $satker->id,
                            'nama_satker' => $satker->nama_satker,
                            'status' => $satker->status
                        ];
                    })
        ]);
    }

    public function create()
    {
        return Inertia::render('Satker/Create',[
            'title' => 'Buat Satuan Kerja'
        ]);
    }

    public function store()
    {
        Satker::create(
            Request::validate([
                'title' => ['required', 'max:150'],
                'type_id' => ['required'],
                'details' => ['required']
            ])
        );

        return Redirect::route('satker')->with('success', 'Satuan Kerja berhasil dibuat.');
    }

    public function edit(Satker $satker)
    {
        return Inertia::render('KnowledgeBase/Edit', [
            'title' => $satker->title,
            'satker' => [
                'id' => $satker->id,
                'title' => $satker->title,
                'type_id' => $satker->type_id,
                'details' => $satker->details,
            ],
        ]);
    }

    public function update(Satker $satker)
    {
        $satker->update(
            Request::validate([
                'title' => ['required', 'max:150'],
                'type_id' => ['nullable'],
                'details' => ['required']
            ])
        );

        return Redirect::back()->with('success', 'Satuan Kerja sudah di perbaharui.');
    }

    public function destroy(Satker $satker)
    {
        $satker->delete();
        return Redirect::route('knowledge_base')->with('success', 'Satuan Kerja di hapus.');
    }

}
