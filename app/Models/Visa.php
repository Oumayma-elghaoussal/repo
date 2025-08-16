<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    protected $fillable = [
        'numero',
        'destinataire',
        'sujet',
        'service_concerne',
        'date',
        'numero_correspondance', 
        'fichier',
    ];

}
