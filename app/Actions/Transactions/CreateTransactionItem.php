<?php

namespace App\Actions\Transactions;

use App\Models\TransactionItem;
use App\Actions\Products\StockMovementAction;
use App\Models\Product;
use App\Models\Transaction;
use Exception;

class CreateTransactionItem
{
    public function execute(array $data, Transaction $transaction, $useStockOptname = true)
    {
        if ($data['product_id']) {
            // find product on master Data
            $product = Product::find($data['product_id']);
            // Update stock and store use stock opname
            if (!$useStockOptname) {
                return;
            }

            throw_if($product->stock < $data['quantity'], new Exception("Stock product tidak cukup!"));

            StockMovementAction::increase($product, $data['quantity']);
        }

        // Store transaction Item
        $transaction->products()->create($data);
    }
}
