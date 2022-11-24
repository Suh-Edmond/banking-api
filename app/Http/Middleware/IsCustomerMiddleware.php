<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use App\Constants\Roles;

class IsCustomerMiddleware
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->user()->hasRole(Role::where('name',Roles::CUSTOMER))){
            return $next($request);
        }
        return ResponseTrait::sendError('Access denied', 'You dont have the role to access this route', 403);
    }
}
