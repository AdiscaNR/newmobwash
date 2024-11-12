<?php

namespace App\Http\Controllers\Transaction;

use App\Exports\TransactionsExport;
use App\Models\PaymentType;
use App\Models\Client;
use App\Models\Crew;
use App\Models\Transaction;
use App\Models\TransactionCrew;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TransactionController extends Controller
{
  public function index() {}

  public function create()
  {
    $data['crew'] = Crew::all();

    return view('pages.transaction.create', $data);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'date' => 'required',
      'client_name' => 'required|string',
      'client_address' => 'required',
      'client_phone' => 'required',
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();

    try {
      $clientId = $request->client_id ?? null;

      if (!$clientId) {
        $client = Client::create([
          'name' => $request->client_name,
          'address' => $request->client_address,
          'phone' => $request->client_phone,
        ]);
      }

      $tx = Transaction::create([
        'client_id' => $clientId ?? $client->id,
        'date' => $request->date,
        'created_user' => Auth::user()->id,
      ]);

      foreach ($request->crew as $key => $c) {
        TransactionCrew::create([
          'tx_id' => $tx->id,
          'crew_id' => $c,
        ]);
      }

      DB::commit();
      return Redirect::to('/trx/' . $tx->id);
    } catch (\Throwable $th) {
      throw $th;
      DB::rollback();

      return redirect()->back()->withErrors($validator)->withInput();
    }
  }

  public function view(string $id)
  {
    $data['id'] = $id;
    return view('pages.transaction.view', $data);
  }

  public function edit(string $id)
  {
    return view('pages.transaction.edit');
  }

  public function update(Request $request, string $id) {}

  public function destroy(string $id) {}

  public function order(string $trx)
  {
    $data['tx'] = Transaction::findOrFail($trx);

    $data['payments'] = PaymentType::all();

    $available = $data['tx']->done ? false : true;
    if (!$available) {
      abort(404); // Mengembalikan 404 jika item tidak ditemukan
    }

    return view('pages.transaction.order', $data);
  }

  public function orderStore(Request $request, string $id)
  {
    $trx = Transaction::find($id);

    // dd($trx);
    // dd($request->all());

    DB::beginTransaction();
    try {
      if ($request->before) {
        $before = $this->storeFile($request->before, 'file/bukti');
      }
      if ($request->after) {
        $after = $this->storeFile($request->after, 'file/bukti');
      }

      $trx->update([
        'check_in' => $request->checkin,
        'check_out' => $request->checkout,
        'before' => $before ?? null,
        'after' => $after ?? null,
        'done' => 1,
      ]);

      $service = $request->service_description;
      $qty = $request->qty;
      $price = $request->price;

      foreach ($service as $key => $value) {
        Service::create([
          'name' => $service[$key],
          'qty' => $qty[$key],
          'price' => $price[$key],
          'tx_id' => $trx->id,
        ]);
      }

      $total = 0;

      for ($i = 0; $i < count($qty); $i++) {
        $total += $qty[$i] * $price[$i];
      }

      $payment = Payment::create([
        'tx_id' => $trx->id,
        'payment_type' => $request->payment,
        'total' => $total,
      ]);

      DB::commit();
      return Redirect::to('order/success');
    } catch (\Throwable $th) {
      throw $th;
      DB::rollBack();

      return redirect()->back()->withInput();
    }
  }

  public function orderSuccess()
  {
    return view('pages.transaction.success');
  }

  public function export(Request $request)
  {
    $spreadsheet = new Spreadsheet();
    $activeWorksheet = $spreadsheet->getActiveSheet();

    $start = $request->start;
    $end = $request->end;
    $done = $request->done;

    $data = Transaction::when($start && $end, function ($query) use ($start, $end) {
      return $query->whereBetween('date', [$start, $end]);
    })
      ->when($done, function ($query) use ($done) {
        return $query->where('done', $done);
      })
      ->where('done', 1)
      ->orderBy('date', 'asc')
      ->get();

    // dd($data[0]->crews->pluck('name'));

    // header
    $activeWorksheet->setCellValue('A1', 'Number');
    $activeWorksheet->setCellValue('B1', 'Date');
    $activeWorksheet->setCellValue('C1', 'Client Name');
    $activeWorksheet->setCellValue('D1', 'Client Address');
    $activeWorksheet->setCellValue('E1', 'Client Phone');
    $activeWorksheet->setCellValue('F1', 'Crew');
    $activeWorksheet->setCellValue('G1', 'Check In');
    $activeWorksheet->setCellValue('H1', 'Check Out');
    $activeWorksheet->setCellValue('I1', 'Services');
    $activeWorksheet->setCellValue('J1', 'Qty');
    $activeWorksheet->setCellValue('K1', 'Price');
    $activeWorksheet->setCellValue('L1', 'Total');
    $activeWorksheet->setCellValue('M1', 'Total Amount');
    $activeWorksheet->setCellValue('N1', 'Payment');
    $activeWorksheet->setCellValue('O1', 'Before');
    $activeWorksheet->setCellValue('P1', 'After');

    // styling
    $activeWorksheet->getColumnDimension('A')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('B')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('C')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('D')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('E')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('F')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('G')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('H')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('I')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('J')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('K')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('L')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('M')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('N')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('O')->setAutoSize(true);
    $activeWorksheet->getColumnDimension('P')->setAutoSize(true);
    // data
    Carbon::setLocale('id');

    foreach ($data as $key => $value) {
      $activeWorksheet->setCellValue('A' . ($key + 2), $key + 1);
      $activeWorksheet->setCellValue('B' . ($key + 2), Carbon::parse($value->date)->translatedFormat('l, d F Y'));
      $activeWorksheet->setCellValue('C' . ($key + 2), $value->client->name);
      $activeWorksheet->setCellValue('D' . ($key + 2), $value->client->address);
      $activeWorksheet->setCellValue('E' . ($key + 2), $value->client->phone);
      // $crews = $value->crews->name;

      $activeWorksheet->setCellValue('F' . ($key + 2), implode("\n", $value->crews->pluck('name')->toArray()));
      $activeWorksheet->getStyle('F' . ($key + 2))->getAlignment()->setWrapText(true);


      $activeWorksheet->setCellValue('G' . ($key + 2), $value->check_in);
      $activeWorksheet->setCellValue('H' . ($key + 2), $value->check_out);
      $services = $value->services->toArray();

      $formattedServices = [];
      $formattedQtys = [];
      $formattedPrices = [];
      $formattedTotals = [];

      foreach ($services as $service) {
        $formattedServices[] = $service['name'];
        $formattedQtys[] = $service['qty'];
        $formattedPrices[] = $this->formatRupiah($service['price']);
        $formattedTotals[] = $this->formatRupiah($service['qty'] * $service['price']);
      }

      // Gabungkan nilai yang diformat dengan "\n" (newline)
      $activeWorksheet->setCellValue('I' . ($key + 2), implode("\n", $formattedServices));
      $activeWorksheet->setCellValue('J' . ($key + 2), implode("\n", $formattedQtys));
      $activeWorksheet->setCellValue('K' . ($key + 2), implode("\n", $formattedPrices));
      $activeWorksheet->setCellValue('L' . ($key + 2), implode("\n", $formattedTotals));


      // Mengaktifkan opsi wrap text untuk cell agar baris baru terlihat
      $activeWorksheet->getStyle('I' . ($key + 2))->getAlignment()->setWrapText(true);
      $activeWorksheet->getStyle('J' . ($key + 2))->getAlignment()->setWrapText(true);
      $activeWorksheet->getStyle('K' . ($key + 2))->getAlignment()->setWrapText(true);
      $activeWorksheet->getStyle('L' . ($key + 2))->getAlignment()->setWrapText(true);


      $activeWorksheet->setCellValue('M' . ($key + 2), $value->payment ? $this->formatRupiah($value->payment->total) : '');

      $activeWorksheet->setCellValue('N' . ($key + 2), $value->payment ? $value->payment->type->name : '');
      if ($value->before) {
        $activeWorksheet->setCellValue('O' . ($key + 2), $value->before ? 'Link' : '');
        $activeWorksheet->getCell('O' . ($key + 2))->getHyperlink()->setUrl(url($value->before));
      }
      if ($value->after) {
        $activeWorksheet->setCellValue('P' . ($key + 2), $value->after ? 'Link' : '');
        $activeWorksheet->getCell('P' . ($key + 2))->getHyperlink()->setUrl(url($value->after));
      }
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save('recap.xlsx');
    $filePath = public_path('recap.xlsx');
    return response()->download($filePath, 'recap.xlsx');
    // return Excel::download(new TransactionsExport, 'recap.xlsx');
  }
}
