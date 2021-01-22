<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Events\Message;

class CommandeController extends Controller
{
    public function creerCommande()
    {
        date_default_timezone_set('Europe/Paris');
        $message = json_encode(array('numero' => request()->numero, 'heure_reception' => date('H:i:s')));
        // Emetteur sur le channel "Livreur"
        broadcast(new Message($message, 'Livreur'));

        return response()->json(['test' => 'ok']);
    }
}
