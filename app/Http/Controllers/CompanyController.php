<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function getBalance()
    {
        $total = DB::select("select SUM(tr_commission) as total from transactions");
        return Response(["Total" => $total[0]->total]);
    }
}
