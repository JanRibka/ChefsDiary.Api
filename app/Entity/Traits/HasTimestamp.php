<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping\Column;

trait HasTimestamp
{
    #[Column]
    private DateTime $CreatedAt;


    // Getters
    public function getCreatedAt(): DateTime
    {
        return $this->CreatedAt;
    }


    // Setters
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->CreatedAt = $createdAt;

        return $this;
    }
}