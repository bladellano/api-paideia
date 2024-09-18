<?php

namespace App\Traits;

use App\Models\Module;

trait MenuTrait
{
	protected function generateMenuStructure()
	{
		// Obtém os módulos adquiridos do arquivo .env
		$modulesEnabledInEnv = config('services.ge.modules_acquired');
		$arModulesEnabledInEnv = explode(',', $modulesEnabledInEnv);

		$modules = Module::with('moduleMenus')->get();
		$menuArray = [];

		foreach ($modules as $module) {

			if (in_array(trim($module->nm_machine), array_map('trim', $arModulesEnabledInEnv))) {

				$children = [];

				foreach ($module->moduleMenus as $menu) {

					if (!$menu->active)
						continue;

					$children[] = [
						'action' => $menu->action,
						'path' => ['name' => $menu->path]
					];
				}
				$menuArray[] = [
					'text' => $module->nm_module,
					'children' => $children
				];
			}
		}

		session(['menu' => $menuArray]);

		return $menuArray;
	}
}
