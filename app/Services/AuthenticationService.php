<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Interfaces\AuthenticationInterface;
use App\Models\Role;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use App\Constants\Roles;

class AuthenticationService implements AuthenticationInterface {

    use ResponseTrait;

    public function registerUser($request)
    {
        $created = User::create([
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'telephone'         => $request->telephone,
            'gender'            => $request->gender,
            'email'             => $request->email,
            'country'           => $request->country,
            'location'          => $request->location,
            'box_number'        => $request->box_number,
            'password'          => Hash::make($request->password),
            'email_verified_at' => now(),
            'dob'               => $request->dob,
            'pob'               => $request->pob
        ]);

        $this->saveUserRole($created, Roles::CUSTOMER);
    }



    public function loginUser($request)
    {
        $user = User::where('telephone', $request->telephone)->orwhere('email', $request->email)->firstOrFail();

        if (!Hash::check($request->password, $user->password)) {

            return $this->sendError('Unauthorized', 'Bad Credentials', 401);

        } else {

            $token = $this->generateToken($user);

            return new UserResource($user, $token);
        }
    }


    private function saveUserRole($user, $roleName)
    {
        $role = Role::where('name', $roleName)->get();
        return $user->roles()->sync($role);
    }


    private function generateToken($user)
    {
        if (!is_null($user)) {
            $token = $user->createToken('access-token', $user->roles->toArray())->plainTextToken;
        }

        return $token;
    }
}

