<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminFinanceController extends Controller
{
    public function index()
    {
        return view('admin.finances.index');
    }
}
