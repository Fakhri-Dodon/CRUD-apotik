<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MedicineController;
use Barryvdh\DomPDF\Facade\Pdf as PDf;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::with('user')->where('created_at', 'Like', '%'.$request->search_date.'%')->simplePaginate(5);
        return view('order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $medicines = Medicine::all();
        return view('order.create', compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_costumer' => 'required', 
            'medicines' => 'required',
        ]);

        // mencari jumlah item yang sama pada array, strukturnya:
        // ["item"> "Jumlah" 1
        $arrayDistinct = array_count_values($request->medicines);
        // menyiapkan array kosong untuk menampung format array baru
        $arrayAssocMedicines = [];
        // looping hasil penghitungan item distinct (duplikat)
        // key akan berupa value dr input medicines (id), item array berupa jumlah penghitungan item duplikat
        foreach($arrayDistinct as $id => $count) {
            // mencari data obat berdasarkan id (obat yg dipilih)
            $medicine = Medicine::where('id', $id)->first();
            // ambil bagian column price dr hasil pencarian lalu kalikan dengan jumlah item duplikat sehingga akan menghasilkan total harga dr
            // pembelian obat tersebut
            if($medicine['stock'] < $count) {
                $valueBefore = [
                    'name_costumer' => $request->name_costumer,
                    'medicines' => $request->medicines,
                ];

                $msg = "Obat " . $medicine['name'] . ", sisa stok : " . $medicine['stock'] . " .Tidak dapat melakukan pembelian";
                return redirect()->back()->withInput()->with('failed', $msg, $valueBefore);
            }else {
                $medicine['stock'] -= $count;
                $medicine->save();
            }

            $subPrice = $medicine['price'] * $count;
            // struktur value column medicines menjadi multidimensi dengan dimensi kedua berbentuk array assoc dengan key "id", "name_medicine", "qty,
            // "price"
            $arrayItem = [
                "id" => $id,
                "name_medicine" => $medicine['name'],
                "qty" => $count,
                "price" => $medicine['price'],
                "sub_price" => $subPrice,
            ];
            // masukkan struktur array tersebut ke array kosong yang sudah disediakan sebelumnya
            array_push($arrayAssocMedicines, $arrayItem);
        }

        $totalPrice = 0;

        foreach($arrayAssocMedicines as $item) {
            $totalPrice += (int)$item['sub_price'];
        }

        $priceWithPpn = $totalPrice + ($totalPrice * 0.01);

        $proses = Order::create([
            'user_id' => Auth::user()->id,
            'medicines' => $arrayAssocMedicines,
            'name_costumer' => $request->name_costumer,
            'total_price' => $priceWithPpn,
        ]);

        if($proses) {
            $order = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
            return redirect()->route('kasir.order.print', $order['id']);
        }else {
            return redirect()->back()->with('failed', 'Gagal membuat data pembelian. Silahkan coba kembali dengan data yang sesuai');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::find($id);
        return view('order.print', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function downloadPdf($id) {

        // ambil data berdasarkan id yang ada di struck dan dipastikan terformat menjadi
        $order = Order::find($id)->toArray();

        // kita akan share data dengan inisial awal agar bisa digunakan ke blade manapun
        view()->share('order', $order);

        // ini akan meload view halaman download nya
        $pdf = PDf::loadView('order.print_pdf', compact('order'));
        return $pdf->download('invoice.pdf');
    }
}
