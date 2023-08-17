<?php

namespace RecursiveTree\Seat\TransportPlugin\Item;

use RecursiveTree\Seat\PricesCore\Contracts\Priceable;
use RecursiveTree\Seat\TreeLib\Items\EveItem;

/**
 * @property int $amount
 * @property float $price
 */
class PriceableEveItem extends EveItem implements Priceable
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