<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use GhoniJee\DxAdapter\QueryAdapter;
use App\Actions\Transactions\CreateTransaction;
use App\Actions\Transactions\CreateTransactionItem;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $data = QueryAdapter::for(Transaction::class, $request)->with('products')->paginate($request->take ?? 10);
        // dd($data->items();
        return $this->responseData($data->items())
            ->success();
    }

    public function store(Request $request, CreateTransaction $createTransaction, CreateTransactionItem $createTransactionItem)
    {
        try {
            DB::beginTransaction();

            // Create Transaction
            $transactionModel = $createTransaction->execute($request->all())
                ->createItems($createTransactionItem)
                ->getTransaction();

            DB::commit();

            return $this->responseMessage("Store data success")
                ->success();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseMessage($th->getMessage() . $th->getTraceAsString())->failed();
        }
    }
}
