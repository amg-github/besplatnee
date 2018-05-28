<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
	protected $besplatnee;

	public function __construct(\App\Facades\Besplatnee $besplatnee) 
	{
		$this->besplatnee = $besplatnee;
	}

	public function index($alias)
	{
		return $this->besplatnee->headings()->get(1)->name;
	}
}