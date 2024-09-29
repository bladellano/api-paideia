<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Traits\DataClientTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
	use DataClientTrait;

	//! @todo Passar para /API
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

	public function edit($id)
	{
		$client = Client::findOrFail($id);
		return view('clients.edit', compact('client'));
	}

	public function update(Request $request, $id)
	{
		$client = Client::findOrFail($id);

		$validatedData = $request->validate([
			'school_name' => 'required|string|max:255',
			'cnpj' => 'required|string|max:20',
			'address' => 'required|string|max:255',
			'phones' => 'required|string',
			'email' => 'required|string',
			'owner' => 'required|string|max:255',
			'slogan' => 'nullable|string|max:255',
			'main_color' => 'nullable|string|max:255',
			'secondary_color' => 'nullable|string|max:255',
			'main_service' => 'nullable|string|max:255',
			'website_name' => 'nullable|string|max:255',
			'city' => 'nullable|string|max:255',
			'average_grade' => 'nullable|numeric',
			'colored_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
			'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
			'black_white_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
		]);

		if ($request->hasFile('colored_logo')) {
			if ($client->colored_logo) {
				Storage::disk('public')->delete(str_replace('/storage/', '', $client->colored_logo));
			}

			$coloredLogoPath = $request->file('colored_logo')->store('logos', 'public');
			$validatedData['colored_logo'] = asset('storage/' . $coloredLogoPath);
		}

		if ($request->hasFile('black_white_logo')) {

			if ($client->black_white_logo) {
				Storage::disk('public')->delete(str_replace('/storage/', '', $client->black_white_logo));
			}
			$bwLogoPath = $request->file('black_white_logo')->store('logos', 'public');
			$validatedData['black_white_logo'] = asset('storage/' . $bwLogoPath);
		}

		if ($request->hasFile('cover')) {

			if ($client->cover) {
				Storage::disk('public')->delete(str_replace('/storage/', '', $client->cover));
			}
			$bwLogoPath = $request->file('cover')->store('logos', 'public');
			$validatedData['cover'] = asset('storage/' . $bwLogoPath);
		}

		$validatedData['phones'] = explode(',', $request->input('phones'));

		$client->update($validatedData);

		return redirect()->route('clients.index')->with('success', 'Client updated successfully!');
	}

	public function store(Request $request)
	{
		$validatedData = $request->validate([
			'school_name' => 'required|string|max:255',
			'cnpj' => 'required|string|max:20',
			'address' => 'required|string|max:255',
			'phones' => 'required|string',
			'email' => 'required|string',
			'main_color' => 'nullable|string|max:255',
			'secondary_color' => 'nullable|string|max:255',
			'owner' => 'required|string|max:255',
			'slogan' => 'nullable|string|max:255',
			'main_service' => 'nullable|string|max:255',
			'website_name' => 'nullable|string|max:255',
			'city' => 'nullable|string|max:255',
			'average_grade' => 'nullable|string',
			'colored_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
			'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

		if ($request->hasFile('cover')) {
			$bwLogoPath = $request->file('cover')->store('logos', 'public');
			$validatedData['cover'] = asset('storage/' . $bwLogoPath);
		}

		$validatedData['phones'] = explode(',', $request->input('phones'));

		Client::create($validatedData);

		return redirect()->route('clients.index')->with('success', 'Client created successfully!');
	}

	public function destroy($id)
	{
		$client = Client::findOrFail($id);

		if ($client->colored_logo)
			Storage::disk('public')->delete(str_replace('/storage/', '', $client->colored_logo));


		if ($client->black_white_logo)
			Storage::disk('public')->delete(str_replace('/storage/', '', $client->black_white_logo));

		$client->delete();

		return redirect()->route('clients.index')->with('success', 'Client deleted successfully!');
	}
}
