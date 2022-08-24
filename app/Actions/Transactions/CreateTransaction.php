<?php

namespace App\Actions\Transactions;

use App\Models\Store;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CreateTransaction
{
    public Transaction $transaction;

    public Collection $data;

    public CreateTransactionItem $itemAction;

    public function execute(array $data)
    {
        $this->data = collect($data);

        $this->transaction = Transaction::create($this->data->except('products')->toArray());

        return $this;
    }

    public function createItems(CreateTransactionItem $createTransactionItem)
    {
        $this->itemAction = $createTransactionItem;
        $items = collect($this->data['products']);

        $items->each(function ($item) {
            $this->itemAction->execute($item, $this->transaction);
        });

        return $this;
    }

    public function getTransaction()
    {
        return $this->transaction->load('products');
    }
}
