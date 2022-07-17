<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Transactions\CreateTransaction;
use App\Actions\Transactions\CreateTransactionItem;

class TransactionController extends Controller
{
    public function store(Request $request, CreateTransaction $createTransaction, CreateTransactionItem $createTransactionItem)
    {
        try {
            DB::beginTransaction();

            /**
             * Step by Step :
             * 1. get Data User Login | Done from mobile
             * 2. Create transaction | done
             * 3. Create Transaction Item | done
             * 4. increase stock for each product out | done
             */
            // Create Transaction
            $transactionModel = $createTransaction->execute($request->all())
                ->createItems($createTransactionItem)
                ->getTransaction();

            // dd($transactionModel);
            DB::commit();

            return $this->responseMessage("Store data success")
                ->success();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseMessage($th->getMessage() . $th->getTraceAsString())->failed();
        }
    }
}
