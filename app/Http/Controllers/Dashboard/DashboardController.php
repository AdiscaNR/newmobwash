<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index()
  {
    return view('pages.dashboard.index');
  }

  public function recap(Request $request)
  {
    $start = $request->start ?? null;
    $end = $request->end ?? null;

    $done = $request->done;

    $data['tx']= [];
    if ($start || $end) {

      $data['tx'] = Transaction::when($start && $end, function ($query) use ($start, $end) {
        return $query->whereBetween('date', [$start, $end]);
      })
        ->when($done, function ($query) use ($done) {
          return $query->where('done', $done);
        })
        ->orderBy('date', 'asc')
        ->get();
    }
    return view('pages.recap.index', $data);
  }
}
