<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use App\Constants\Roles;
use App\Models\CustomRole;

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
        if($request->user()->hasRole(CustomRole::findByName(Roles::CUSTOMER, 'api'))){
            return $next($request);
        }
        return ResponseTrait::sendError('Access denied', 'You dont have the role to make this request', 403);
    }
}
