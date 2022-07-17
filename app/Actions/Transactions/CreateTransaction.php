<?php

namespace App\Actions\Transactions;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;

class CreateTransaction
{
    public Transaction $transaction;

    public Collection $data;

    public CreateTransactionItem $itemAction;

    public function execute(array $data)
    {
        $this->data = collect($data);

        $this->transaction = Transaction::create($this->data->except('items')->toArray());

        return $this;
    }

    public function createItems(CreateTransactionItem $createTransactionItem)
    {
        $this->itemAction = $createTransactionItem;
        $items = collect($this->data['items']);

        $items->each(function ($item) {
            $this->itemAction->execute($item, $this->transaction);
        });

        return $this;
    }

    public function getTransaction()
    {
        return $this->transaction;
    }
}
