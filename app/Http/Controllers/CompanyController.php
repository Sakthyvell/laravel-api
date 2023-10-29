<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function getBalance()
    {
        $total = DB::select("select SUM(tr_commission_amount) as total from transactions");
        return Response(["Total" => $total[0]->total]);
    }
}
