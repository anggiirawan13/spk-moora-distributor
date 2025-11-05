<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Distributor;
use App\Models\BusinessScale;
use App\Models\ProductCategory;
use App\Models\DeliveryMethod;
use App\Models\PaymentTerm;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $distributors = Distributor::count();
        $users = User::count();
        $paymentTerms = PaymentTerm::count();
        $productCategories = ProductCategory::count();
        $deliveryMethods = DeliveryMethod::count();
        $businessScales = BusinessScale::count();
        $criteria = Criteria::count();
        $alternative = Alternative::count();

        $data = (object) [
            'businessScales' => $businessScales,
            'deliveryMethods' => $deliveryMethods,
            'productCategories' => $productCategories,
            'paymentTerms' => $paymentTerms,
            'distributors' => $distributors,
            'users' => $users,
            'criteria' => $criteria,
            'alternative' => $alternative,
        ];

        return view('admin.dashboard', compact('data'));
    }
}