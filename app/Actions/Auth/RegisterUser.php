<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;

readonly class RegisterUser
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly Hasher $hasher, private Dispatcher $dispatcher)
    {
        //
    }

    public function execute(array $data)
    {
        $user = User::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $this->hasher->make($data['password']), // we could remove this and it would still work as we cast on User model
            ]
        );

        $this->dispatcher->dispatch(new Registered($user));

        return $user;
    }
}
