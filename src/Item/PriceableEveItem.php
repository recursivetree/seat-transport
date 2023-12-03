<?php

namespace RecursiveTree\Seat\TransportPlugin\Item;


use RecursiveTree\Seat\TreeLib\Items\EveItem;
use Seat\Services\Contracts\IPriceable;

/**
 * @property int $amount
 * @property float $price
 */
class PriceableEveItem extends EveItem implements IPriceable
{

    public function getTypeID(): int
    {
        return $this->typeModel->typeID;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}