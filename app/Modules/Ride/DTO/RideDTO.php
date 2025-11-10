<?php

namespace App\Modules\Ride\DTO;

class RideDTO
{
    public ?int $id;
    public ?int $driver_id;
    public ?int $passenger_id;
    public ?array $geometry;
    public ?float $distance;
    public ?float $duration;
    public ?int $price;
    public ?array $from;
    public ?array $to;
    public ?string $from_address;
    public ?string $to_address;
    public ?string $created_at;
    public ?string $updated_at;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->driver_id = $data['driver_id'] ?? null;
        $this->passenger_id = $data['passenger_id'] ?? null;
        $this->distance = $data['distance'] ?? null;
        $this->duration = $data['duration'] ?? null;
        $this->geometry = $data['geometry'] ?? null;
        $this->price = $data['price'] ?? null;
        $this->from = $data['from'] ?? null;
        $this->to = $data['to'] ?? null;
        $this->from_address = $data['from_address'] ?? null;
        $this->to_address = $data['to_address'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public static function fromArray(array $data): self
    {
        $dto = new self();
        foreach ($data as $key => $value) {
            if (property_exists($dto, $key)) {
                $dto->$key = $value;
            }
        }
        return $dto;
    }

    public function toArray(): array
    {
        $data = get_object_vars($this);

        return array_filter($data, fn($value) => $value !== null);
    }
}
