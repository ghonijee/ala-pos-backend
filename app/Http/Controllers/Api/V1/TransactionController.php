<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Transactions\CreateInvoiceNumber;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use GhoniJee\DxAdapter\QueryAdapter;
use App\Actions\Transactions\CreateTransaction;
use App\Actions\Transactions\CreateTransactionItem;
use App\Models\Store;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $data = QueryAdapter::for(Transaction::class, $request)->with('products')->paginate($request->take ?? 10);
        return $this->responseData($data->items())
            ->success();
    }

    public function listGroup(Request $request)
    {
        try {
            $data = QueryAdapter::for(Transaction::class, $request)->get();
            return $this->responseData($data)
                ->success();
        } catch (\Throwable $th) {
            return $this->responseMessage($th->getMessage() . $th->getTraceAsString())->failed();
        }
    }

    public function store(Request $request, CreateInvoiceNumber $createInvoiceNumber, CreateTransaction $createTransaction, CreateTransactionItem $createTransactionItem)
    {
        try {
            DB::beginTransaction();

            // Generate invoice number
            $invoiceNumber = $createInvoiceNumber->setup(Transaction::query(), $request->store_id);

            // Merge on request data
            $request->merge([
                "invoice_number" => $invoiceNumber->generateNumber(),
                "sequence_number" => $invoiceNumber->nextSequence()
            ]);

            // Create Transaction
            $transactionModel = $createTransaction->execute($request->all())
                ->createItems($createTransactionItem)
                ->getTransaction();

            DB::commit();

            return $this->responseMessage("Store data success")->responseData($transactionModel)
                ->success();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseMessage($th->getMessage() . $th->getTraceAsString())->failed();
        }
    }
}
