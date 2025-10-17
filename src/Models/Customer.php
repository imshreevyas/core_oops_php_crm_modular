<?php
namespace coreCRM\Models;
use CoreCRM\Traits\Timestampable;

class Customer{
    use Timestampable;

    private ?int $id;
    private string $name;
    private ?string $email;
    private ?string $phone;

    public function __construct(string $name, ?string $email = null, ?string $phone = null, ?int $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email ?? '';
        $this->phone = $phone ?? '';
        $this->touchCreated();
    }

    // getters
    public function id(): ?int { return $this->id; }
    public function name(): string { return $this->name; }
    public function email(): string { return $this->email; }
    public function phone(): string { return $this->phone; }

    // setters
    public function setName(string $name): void { $this->name = $name; $this->touchUpdated(); }
    public function setEmail(string $email): void { $this->email = $email; $this->touchUpdated(); }
    public function setPhone(string $phone): void { $this->phone = $phone; $this->touchUpdated(); }
}