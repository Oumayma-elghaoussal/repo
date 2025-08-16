<?php

namespace App\Http\Controllers;

use App\Models\DepartMinisterielle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DepartMinisterielleController extends Controller
{
    public function index(Request $request)
    {
        $query = DepartMinisterielle::query();

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

        return view('departministerielle.index', compact('documents'));
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

        DepartMinisterielle::create([
            'numero' => $request->numero,
            'destinataire' => $request->destinataire,
            'sujet' => $request->sujet,
            'service_concerne' => $request->service_concerne,
            'date' => $request->date,
            'numero_correspondance' => $request->numero_correspondance,
            'fichier' => $filename,
        ]);

        return redirect()->route('departministerielle.index')->with('success', 'Document ajouté avec succès !');
    }

    public function edit($id)
    {
        $doc = DepartMinisterielle::findOrFail($id);
        $documents = DepartMinisterielle::orderBy('date', 'desc')->get();
        return view('departministerielle.index', compact('doc', 'documents'));
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

        $doc = DepartMinisterielle::findOrFail($id);

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

        return redirect()->route('departministerielle.index')->with('success', 'Document modifié avec succès !');
    }

    public function destroy(DepartMinisterielle $departministerielle)
    {
        if ($departministerielle->fichier && Storage::disk('public')->exists('fichiers/' . $departministerielle->fichier)) {
            Storage::disk('public')->delete('fichiers/' . $departministerielle->fichier);
        }

        $departministerielle->delete();

        return redirect()->route('departministerielle.index')->with('success', 'Document supprimé avec succès !');
    }
}
