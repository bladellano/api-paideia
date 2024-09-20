<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Traits\DataClientTrait;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    use DataClientTrait;

    public function getClientData()
    {
        return $this->getClient();
    }

    public function index()
    {
        $clients = Client::all();

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'school_name' => 'required|string|max:255',
            'cnpj' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'phones' => 'required|string',
            'email' => 'required|string',
            'owner' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'main_service' => 'nullable|string|max:255',
            'website_name' => 'nullable|string|max:255',
            'colored_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'black_white_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('colored_logo')) {
            $coloredLogoPath = $request->file('colored_logo')->store('logos', 'public');
            $validatedData['colored_logo'] = asset('storage/' . $coloredLogoPath);
        }

        if ($request->hasFile('black_white_logo')) {
            $bwLogoPath = $request->file('black_white_logo')->store('logos', 'public');
            $validatedData['black_white_logo'] = asset('storage/' . $bwLogoPath);
        }

        $validatedData['phones'] = explode(',', $request->input('phones'));

        Client::create($validatedData);

        return redirect()->route('clients.create')->with('success', 'Client created successfully!');
    }
}
