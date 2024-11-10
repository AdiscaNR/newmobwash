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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class TransactionController extends Controller
{
    public function index()
    {
    }

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
            $client = Client::create([
                'name' => $request->client_name,
                'address' => $request->client_address,
                'phone' => $request->client_phone,
            ]);

            $tx = Transaction::create([
                'client_id' => $client->id,
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

    public function update(Request $request, string $id)
    {
    }

    public function destroy(string $id)
    {
    }

    public function order(string $trx)
    {
        $data['tx'] = Transaction::findOrFail($trx);
        $data['crews'] = TransactionCrew::where('tx_id', $data['tx']->id)->get();
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
        return 'Success';
    }

    public function export() {
        return Excel::download(new TransactionsExport, 'recap.xlsx');
    }
}
