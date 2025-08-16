<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arrivee; 
use Illuminate\Support\Facades\Storage;


class ArriveeController extends Controller
{
    public function index(Request $request)
    {
        $query = Arrivee::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'LIKE', "%$search%")
                  ->orWhere('destinataire', 'LIKE', "%$search%")
                  ->orWhere('sujet', 'LIKE', "%$search%")
                  ->orWhere('service_concerne', 'LIKE', "%$search%")
                  ->orWhere('numero_d_envoi', 'LIKE', "%$search%");
            });
        }

        $documents = $query->orderBy('date', 'desc')->get();

        return view('arrivee.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required',
            'destinataire' => 'required',
            'sujet' => 'required',
            'service_concerne' => 'required',
            'date' => 'required|date',
            'numero_d_envoi' => 'required',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // max 2 Mo
        ]);

        $filename = null;
        if ($request->hasFile('fichier')) {
            $filename = $request->file('fichier')->store('fichiers', 'public');
            $filename = basename($filename);
        }

        Arrivee::create([
            'numero' => $request->numero,
            'destinataire' => $request->destinataire,
            'sujet' => $request->sujet,
            'service_concerne' => $request->service_concerne,
            'date' => $request->date,
            'numero_d_envoi' => $request->numero_d_envoi,
            'fichier' => $filename,
        ]);

        return redirect()->route('arrivee.index')->with('success', 'Document ajouté');
    }

    public function edit($id)
    {
        $doc = Arrivee::findOrFail($id);
        $documents = Arrivee::all();
        return view('arrivee.index', compact('doc', 'documents'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'numero' => 'required',
            'destinataire' => 'required',
            'sujet' => 'required',
            'service_concerne' => 'required',
            'date' => 'required|date',
            'numero_d_envoi' => 'required',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $doc = Arrivee::findOrFail($id);

        if ($request->hasFile('fichier')) {
            // Supprimer ancien fichier si existant
            if ($doc->fichier && Storage::disk('public')->exists('fichiers/'.$doc->fichier)) {
                Storage::disk('public')->delete('fichiers/'.$doc->fichier);
            }

            $filename = $request->file('fichier')->store('fichiers', 'public');
            $filename = basename($filename);
        } else {
            $filename = $doc->fichier; // garder ancien fichier
        }

        $doc->update([
            'numero' => $request->numero,
            'destinataire' => $request->destinataire,
            'sujet' => $request->sujet,
            'service_concerne' => $request->service_concerne,
            'date' => $request->date,
            'numero_d_envoi' => $request->numero_d_envoi,
            'fichier' => $filename,
        ]);

        return redirect()->route('arrivee.index')->with('success', 'Document mis à jour');
    }

    public function destroy($id)
    {
        $doc = Arrivee::findOrFail($id);
        // Supprimer fichier associé avant suppression si besoin
        if ($doc->fichier && Storage::disk('public')->exists('fichiers/'.$doc->fichier)) {
            Storage::disk('public')->delete('fichiers/'.$doc->fichier);
        }

        $doc->delete();

        return redirect()->route('arrivee.index')->with('success', 'Document supprimé');
    }
}
