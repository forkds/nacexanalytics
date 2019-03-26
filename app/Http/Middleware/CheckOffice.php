<?php

namespace App\Http\Middleware;

use Closure;
use App\OfficeUser;


class CheckOffice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        # Check if offices assigned to user
        $offices = $request->user()->hasOffices();

        # if not ...
        if (!$offices)
        {
            # Critical error !!!
            \Auth::logout();
            abort (301, trans('nacex-analytics.err_active_office'));
        }

        # Check if has active office
        $activeOffice = $request->user()->hasActiveOffice();
    
        # if not ...
        if (!$activeOffice)
        {
            # Activate first offiice by default
            $model = new OfficeUser;

            $rs = $model->where('user_id',"=", $request->user()->id)->first();
            $rs->active=TRUE;
            $rs->save();
        }

        # Double Check if has active office
        $activeOffice = $request->user()->hasActiveOffice();
    
        # if not ...
        if (!$activeOffice)
        {
            abort (301, trans('nacex-analytics.err_active_office'));
        }

        return $next($request);
    }
}
