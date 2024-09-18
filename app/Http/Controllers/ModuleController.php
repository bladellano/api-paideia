<?php

namespace App\Http\Controllers;


use App\Models\Module;
use App\Traits\MenuTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{

  use MenuTrait;

  public function index()
  {
    $modules = Module::with('moduleMenus')->get();
    return view('modules.index', compact('modules'));
  }

  public function create()
  {
    return view('modules.create');
  }

  public function store(Request $request)
  {
    $module = Module::create($request->all());

    foreach ($request->menus as $menu) {
      $module->moduleMenus()->create($menu);
    }

    return redirect()->route('modules.index');
  }

  public function generateMenu()
  {
    $menuArray = $this->generateMenuStructure();
    session(['menu' => $menuArray]);
    return $menuArray;
  }
}
