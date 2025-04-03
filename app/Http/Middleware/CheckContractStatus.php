<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Contract;

class CheckContractStatus
{
    public function handle(Request $request, Closure $next, $status)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        if($user->user_type == 2){
            $contract = Contract::where('user_id', $user->id)->first();
    
            if (!$contract || $contract->status !== $status) {
                abort(403, 'Unauthorized');
            }
        }

        return $next($request);
    }
}
