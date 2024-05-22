<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $count_all_invoices = Invoice::count();
        $count_unpaid_invoices = Invoice::where('value_status','=',2)->count();

        $nspa_unpaid_invoices = round($count_unpaid_invoices/$count_all_invoices*100,0);


        $count_paid_invoices = Invoice::where('value_status','=',1)->count();

        $nspa_paid_invoices = round($count_paid_invoices/$count_all_invoices*100,0);


        $count_invoices_3 = Invoice::where('value_status','=',3)->count();

        $nspa_invoices_3 = round($count_invoices_3/$count_all_invoices*100,0);


        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 100, 'height' => 50])
            ->labels(['الفواتير الغير مدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => "الفواتير الغير مدفوعة",
                    'backgroundColor' => ['#D80032'],
                    'data' => [$nspa_unpaid_invoices]
                ],
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['#186F65'],
                    'data' => [$nspa_paid_invoices]
                ],
                [
                    "label" => "الفواتير المدفوعة جزئيا",
                    'backgroundColor' => ['#F99417'],
                    'data' => [$nspa_invoices_3]
                ]


            ])
            ->options([]);

        return view('dashboard', compact('chartjs'));
    }
}
