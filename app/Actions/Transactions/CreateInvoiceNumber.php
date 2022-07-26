<?php

namespace App\Actions\Transactions;

class CreateInvoiceNumber
{
    private int $number_sequence_length = 4;

    private String $prefix = "TR";

    private $model;

    private $store_id;

    private int $lastSequence = 0;

    public function setup($model, $store_id)
    {
        $this->model = $model;
        $this->store_id = $store_id;
        $this->lastSequenceToday();

        return $this;
    }

    private function lastSequenceToday(): void
    {

        $last = $this->model->where("date", now()->format('Y-m-d'))->where("store_id", $this->store_id)->orderBy("sequence_number", "DESC")->first('sequence_number');

        $this->lastSequence = $last == null ? 0 : $last->sequence_number;
    }

    public function generateNumber(): String
    {
        $string = sprintf(
            "%s%s-%0{$this->number_sequence_length}d",
            $this->prefix,
            now()->format("Ymd"),
            $this->lastSequence + 1
        );

        return $string;
    }

    public function nextSequence(): int
    {
        return $this->lastSequence + 1;
    }
}
