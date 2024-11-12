<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
  use AuthorizesRequests, ValidatesRequests;

  public function response_json($status, $message, $data = null)
  {
    if ($data) {
      return response()->json([
        'status' => $status,
        'message' => $message,
        'data' => $data
      ]);
    } else {
      return response()->json([
        'status' => $status,
        'message' => $message,
      ]);
    }
  }

  public function storeFile($file, $path)
  {
    // $filename = time() . $file->getClientOriginalName();
    // $file->move($path, $filename);

    $pathFile = $file->store($path, 'public');
    $url = Storage::url($pathFile);
    return $url;
  }

  public function deleteFile($file)
  {
    $path = str_replace('/storage/', '', $file);
    if (Storage::disk('public')->exists($path)) {
      return Storage::disk('public')->delete($path);
    }

    return 'not found';
  }

  function formatRupiah($value)
  {
    return 'Rp ' . number_format($value, 0, ',', '.');
  }
}
