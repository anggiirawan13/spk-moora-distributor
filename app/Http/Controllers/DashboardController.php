<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Distributor;
use App\Models\BusinessScale;
use App\Models\Product;
use App\Models\DeliveryMethod;
use App\Models\PaymentTerm;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $distributors = Distributor::visibleTo($user)->count();
        $users = User::count();
        $paymentTerms = PaymentTerm::visibleTo($user)->count();
        $product = Product::visibleTo($user)->count();
        $deliveryMethods = DeliveryMethod::visibleTo($user)->count();
        $businessScales = BusinessScale::visibleTo($user)->count();
        $criteria = Criteria::visibleTo($user)->count();
        $alternative = Alternative::visibleTo($user)->count();

        $data = (object) [
            'businessScales' => $businessScales,
            'deliveryMethods' => $deliveryMethods,
            'product' => $product,
            'paymentTerms' => $paymentTerms,
            'distributors' => $distributors,
            'users' => $users,
            'criteria' => $criteria,
            'alternative' => $alternative,
        ];

        return view('dashboard', compact('data'));
    }
}
