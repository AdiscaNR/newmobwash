<?php

namespace App\Http\Controllers\Crew;

use App\Http\Controllers\Controller;
use App\Models\Crew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CrewController extends Controller
{
  public function index()
  {
    $data['crews'] = Crew::paginate(10);

    return view('pages.crew.index', $data);
  }

  public function create()
  {
    return view('pages.crew.create');
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string'
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();

    try {
      $store = Crew::create(
        [
          'name' => $request->name
        ]
      );

      DB::commit();

      notify()->success('Crew added!');

      return Redirect::to('/crew');
    } catch (\Throwable $th) {
      throw $th;
      DB::rollback();

      return redirect()->back()->withErrors($validator)->withInput();
    }
  }

  public function edit(string $id)
  {
    $data['crew'] = Crew::find($id);

    return view('pages.crew.edit', $data);
  }

  public function update(Request $request, string $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string'
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();

    try {

      $crew = Crew::find($id)->update(
        [
          'name' => $request->name
        ]
      );

      DB::commit();

      notify()->success('Crew updated!');

      return Redirect::to('/crew');
    } catch (\Throwable $th) {
      throw $th;
      DB::rollback();

      return redirect()->back()->withErrors($validator)->withInput();
    }
  }

  public function destroy(string $id)
  {
    $delete = Crew::find($id)->delete();

    if ($delete) {
      notify()->success('Crew deleted!');
      return Redirect::to('/crew');
    }
  }
}
