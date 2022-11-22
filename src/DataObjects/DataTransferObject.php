<?php

namespace Kali\MessageBroker\DataObjects;

class DataTransferObject
{
    /**
     * Return the data as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return (array) $this;
    }

    /**
     * Summary of toJson
     * @return bool|string
     */
    public function toJson(): bool|string
    {
        return json_encode($this);
    }
}