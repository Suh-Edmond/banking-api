<?php

namespace App\Services\Auth;

use App\Http\Resources\UserResource;
use App\Interfaces\Auth\AuthenticationInterface;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Constants\Roles;
use App\Models\CustomRole;
use Exception;
use Illuminate\Validation\UnauthorizedException;

class AuthenticationService implements AuthenticationInterface {

    public function registerUser($request)
    {
      try {
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
            'dob'               => $request->dob,
            'pob'               => $request->pob
        ]);

        $created->assignRole(CustomRole::findByName(Roles::CUSTOMER, 'api'));

      } catch (\Exception $e) {
        throw new Exception("Could not create use account". $e->getMessage());
      }
    }



    public function loginUser($request): UserResource
    {
        $user = User::where('email', $request->email)->first();

        if (is_null($user) || !Hash::check($request->password, $user->password)) {
            throw new UnauthorizedException("Invalid credentials! Please try again.");
        }

        $token = $this->generateToken($user);
        return new UserResource($user, $token);
    }



    private function generateToken($user)
    {
        $token = null;
        if (!is_null($user)) {
            $token = $user->createToken('access-token', $user->roles->toArray())->plainTextToken;
        }

        return $token;
    }
}

