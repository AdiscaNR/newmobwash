<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function index(Request $request)
  {
    $year = $request->year ?? Carbon::now()->year;

    $month = $request->month ?? Carbon::now()->month;

    $data['trx_pending'] = Transaction::where('done', 0)->whereYear('date', $year)->count();
    $data['trx_done'] = Transaction::where('done', 1)->whereYear('date', $year)->count();

    $data['trx_pending_month'] = Transaction::where('done', 0)->whereYear('date', $year)->whereMonth('date', $month)->count();
    $data['trx_done_month'] = Transaction::where('done', 1)->whereYear('date', $year)->whereMonth('date', $month)->count();


    return view('pages.dashboard.index', $data);
  }

  public function recap(Request $request)
  {
    $start = $request->start ?? null;
    $end = $request->end ?? null;

    $done = $request->done;

    $data['tx'] = [];
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

  public function data(Request $request)
  {
    $year = $request->year ?? Carbon::now()->year;

    $month = $request->month ?? Carbon::now()->month;

    $data['pending'] = Transaction::where('done', 0)->whereYear('date', $year)->whereMonth('date', $month)->count();
    $data['done'] = Transaction::where('done', 1)->whereYear('date', $year)->whereMonth('date', $month)->count();
    $data['label'] = ['Pending', 'Done'];

    return $this->response_json(200, 'success', $data);
  }

  public function earning(Request $request)
  {
    // Mendapatkan bulan dan tahun saat ini
    $currentMonth = now()->month;
    $currentYear = now()->year;

    // Menyiapkan array untuk label bulan
    $months = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December"
    ];

    // Menyiapkan array untuk menyimpan total pembayaran per bulan
    $monthlyPayments = array_fill(0, 12, 0); // Inisialisasi array dengan 0 untuk setiap bulan

    // Query untuk mendapatkan total pembayaran per bulan yang ada
    $payments = DB::table('payments')
      ->join('transactions', 'transactions.id', '=', 'payments.tx_id')
      ->select(
        DB::raw('MONTH(payments.created_at) as month'),
        DB::raw('SUM(payments.total) as total_amount')
      )
      ->whereYear('payments.created_at', $currentYear) // Menyaring berdasarkan tahun
      ->groupBy(DB::raw('MONTH(payments.created_at)'))
      ->get();

    // Menyusun data total pembayaran per bulan
    foreach ($payments as $payment) {
      // Pastikan total_amount adalah angka (float), bukan string
      $monthlyPayments[$payment->month - 1] = floatval($payment->total_amount);
    }

    // Memotong array agar hanya mencakup bulan-bulan sampai bulan ini
    $monthlyPayments = array_slice($monthlyPayments, 0, $currentMonth);
    $months = array_slice($months, 0, $currentMonth);

    $data = [
      'labels' => $months,
      'values' => $monthlyPayments
    ];

    // Mengembalikan data dalam format yang diinginkan
    return $this->response_json(200, 'success', $data);
  }
}
