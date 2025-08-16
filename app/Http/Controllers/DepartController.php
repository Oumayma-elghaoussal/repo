<?php

namespace App\Http\Controllers;

use App\Models\Depart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DepartController extends Controller
{
    public function index(Request $request)
    {
        $query = Depart::query();

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

        return view('depart.index', compact('documents'));
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

        Depart::create([
            'numero' => $request->numero,
            'destinataire' => $request->destinataire,
            'sujet' => $request->sujet,
            'service_concerne' => $request->service_concerne,
            'date' => $request->date,
            'numero_correspondance' => $request->numero_correspondance,
            'fichier' => $filename,
        ]);

        return redirect()->route('depart.index')->with('success', 'Document ajouté avec succès !');
    }

    public function destroy(Depart $depart)
    {
        if ($depart->fichier && Storage::disk('public')->exists('fichiers/' . $depart->fichier)) {
            Storage::disk('public')->delete('fichiers/' . $depart->fichier);
        }

        $depart->delete();

        return redirect()->route('depart.index')->with('success', 'Document supprimé avec succès !');
    }

    public function edit($id)
    {
        $doc = Depart::findOrFail($id);
        $documents = Depart::all();
        return view('depart.index', compact('doc', 'documents'));
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

        $doc = Depart::findOrFail($id);

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

        return redirect()->route('depart.index')->with('success', 'Document modifié avec succès !');
    }
}


