<?php
namespace App\Entity;




class TransactionData

{
    private ?array $data= null;

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }
}




