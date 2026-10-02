<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = $user
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('account.index', compact(
            'user',
            'orders'
        ));
    }
}