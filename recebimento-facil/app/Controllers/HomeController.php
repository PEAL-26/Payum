<?php

namespace App\Controllers;

use App\Generics\BaseController;

class HomeController extends BaseController
{
	public function index()
	{
		return view('index');
	}
}
