<?php

namespace App\Repositories\Interfaces;

use App\Models\Customer;

interface CustomerRepositoryInterface
{
    public function findByEmail(string $email): ?Customer;

    public function findByPhone(string $phoneE164): ?Customer;

    public function create(string $name, string $email, string $phoneE164): Customer;
}
