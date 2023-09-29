<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Dcblogdev\MsGraph\Models\MsGraphToken;

class NewMicrosoft365SignInListener
{
    public function handle($event)
    {
        $tokenId = $event->token['token_id'];
        $token = MsGraphToken::find($tokenId)->first();

        $user = User::query()
            ->where('email',$event->token['info']['mail'])
            ->orwhere('email',$event->token['info']['userPrincipalName'])
            ->first();

        $has_permission = Permission::query()
            ->where('user_id',$user->emp_id)
            ->first();

        // check if user is already exists in hrportal
        if($user && $has_permission)
        {
            // update MsGraphToken
            MsGraphToken::findOrfail($tokenId)->update([
                'user_id' => $user->emp_id,
                'email' => $user->email,
            ]);

            Auth::login($user);
        }
        else
        {
            // delete MsGraphToken if not registered in hrportal
            MsGraphToken::findOrfail($tokenId)->delete();
            abort(redirect('unauthorized'));
        }
    }
}
