<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\Persistence\Event\LifecycleEventArgs;

trait HasTimestamp
{
    #[Column(options: ['default' => 'CURRENT_TIMESTAMP'], nullable: false)]
    private DateTime $CreatedAt;


    public function updateTimestamp(LifecycleEventArgs $args): void
    {
        if (!isset($this->createdAt)) {
            $this->createdAt = new DateTime();
        }

        // $this->updatedAt = new DateTime();
    }

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