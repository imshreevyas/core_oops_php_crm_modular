<?php 
namespace CoreCRM\Traits;

trait Timestampable{
    protected ?\DateTime $createdAt = null;
    protected ?\DateTime $updatedAt = null;

    public function touchCreated(): void {
        $this->createdAt = new \DateTime();
    }

    public function touchUpdated(): void {
        $this->updatedAt = new \DateTime();
    }

    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTime { return $this->updatedAt; }
}