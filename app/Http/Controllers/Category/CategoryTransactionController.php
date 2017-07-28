<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        #whereHas -> porque puede haber productos que no tiene transactions
        $transactions = $category->products()->whereHas('transactions')->get()->pluck('transactions')->collapse();
        return $this->showAll($transactions);
    }
}
