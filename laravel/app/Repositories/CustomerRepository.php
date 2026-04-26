<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * @param string $email
     * @return Customer|null
     */
    public function findByEmail(string $email): ?Customer
    {
        return Customer::query()->where('email', $email)->first();
    }

    /**
     * @param string $phoneE164
     * @return Customer|null
     */
    public function findByPhone(string $phoneE164): ?Customer
    {
        return Customer::query()->where('phone_e164', $phoneE164)->first();
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $phoneE164
     * @return Customer
     */
    public function create(string $name, string $email, string $phoneE164): Customer
    {
        return Customer::query()->create([
            'name' => $name,
            'email' => $email,
            'phone_e164' => $phoneE164,
        ]);
    }
}
