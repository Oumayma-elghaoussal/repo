<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartConfidentiel;
use Illuminate\Support\Facades\Storage;

class DepartConfidentielController extends Controller
{
    public function index(Request $request)
    {
        $query = DepartConfidentiel::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'LIKE', "%$search%")
                  ->orWhere('destinataire', 'LIKE', "%$search%")
                  ->orWhere('sujet', 'LIKE', "%$search%")
                  ->orWhere('service_concerne', 'LIKE', "%$search%")
                  ->orWhere('numero_correspondance', 'LIKE', "%$search%");
            });
        }

        $documents = $query->orderBy('date', 'desc')->get();
        return view('departconfidentiel.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required',
            'destinataire' => 'required',
            'sujet' => 'required',
            'service_concerne' => 'required',
            'date' => 'required|date',
            'numero_correspondance' => 'required',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filename = null;
        if ($request->hasFile('fichier')) {
            $filename = $request->file('fichier')->store('fichiers', 'public');
            $filename = basename($filename);
        }

        DepartConfidentiel::create([
            'numero' => $request->numero,
            'destinataire' => $request->destinataire,
            'sujet' => $request->sujet,
            'service_concerne' => $request->service_concerne,
            'date' => $request->date,
            'numero_correspondance' => $request->numero_correspondance,
            'fichier' => $filename,
        ]);

        return redirect()->route('departconfidentiel.index')->with('success', 'Document ajouté avec succès !');
    }

    public function edit($id)
    {
        $doc = DepartConfidentiel::findOrFail($id);
        $documents = DepartConfidentiel::all();
        return view('departconfidentiel.index', compact('doc', 'documents'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'numero' => 'required',
            'destinataire' => 'required',
            'sujet' => 'required',
            'service_concerne' => 'required',
            'date' => 'required|date',
            'numero_correspondance' => 'required',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $doc = DepartConfidentiel::findOrFail($id);

        if ($request->hasFile('fichier')) {
            if ($doc->fichier && Storage::disk('public')->exists('fichiers/' . $doc->fichier)) {
                Storage::disk('public')->delete('fichiers/' . $doc->fichier);
            }
            $filename = $request->file('fichier')->store('fichiers', 'public');
            $filename = basename($filename);
        } else {
            $filename = $doc->fichier;
        }

        $doc->update([
            'numero' => $request->numero,
            'destinataire' => $request->destinataire,
            'sujet' => $request->sujet,
            'service_concerne' => $request->service_concerne,
            'date' => $request->date,
            'numero_correspondance' => $request->numero_correspondance,
            'fichier' => $filename,
        ]);

        return redirect()->route('departconfidentiel.index')->with('success', 'Document modifié avec succès !');
    }

    public function destroy($id)
    {
        $doc = DepartConfidentiel::findOrFail($id);

        if ($doc->fichier && Storage::disk('public')->exists('fichiers/' . $doc->fichier)) {
            Storage::disk('public')->delete('fichiers/' . $doc->fichier);
        }

        $doc->delete();

        return redirect()->route('departconfidentiel.index')->with('success', 'Document supprimé avec succès !');
    }
}
