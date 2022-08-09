<?php

namespace App\Actions\Transactions;

use App\Models\TransactionItem;
use App\Actions\Products\StockMovementAction;
use App\Models\Product;
use App\Models\Transaction;

class CreateTransactionItem
{
    public function execute(array $data, Transaction $transaction, $useStockOptname = true)
    {
        if ($data['product_id']) {
            // find product on master Data
            $product = Product::find($data['product_id']);
            // Update stock and store use stock opname
            if ($useStockOptname) {
                StockMovementAction::increase($product, $data['quantity']);
            }
        }

        // Store transaction Item
        $transaction->products()->create($data);
    }
}
