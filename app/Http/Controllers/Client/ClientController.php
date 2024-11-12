<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{

  public function store(Request $request) {
    $store = Client::create([
      'name' => $request->name,
      'address' => $request->address,
      'phone' => $request->phone,
    ]);

    if ($store) {
      
    }
  }
  public function search(Request $request)
  {
    $query = $request->input('query');

    $results = Client::where('name', 'like', '%' . $query . '%')->limit(10)->get(['id', 'name', 'address', 'phone']);

    if (sizeof($results) != 0) {
      return $this->response_json(200, 'success', $results);
    } else {
      return $this->response_json(404, 'not found');
    }
  }
}
