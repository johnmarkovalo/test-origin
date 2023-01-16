<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Occupant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Change password of user
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $currentUser = Auth::user();

        $oldPassword = $request->validated()['old_password'];
        $newPassword = $request->validated()['new_password'];

        if (Hash::check($oldPassword, $currentUser->password)) {
            $currentUser->password = $newPassword;
            $currentUser->save();
            return response('Password changed successfully');
        } else {
            return response('Old password does not match', 401);
        }
    }

    /**
     * Get dashboard data
     */
    public function dashboard()
    {
        $occupants = Occupant::all();
        $positive = $occupants->where('type', 'COVID')->count();
        $discharged = $occupants->where('status', 'DISCHARGED')->count();
        $vaccinated = $occupants->where('vaccination', 'VACCINATED')->count();
        $deceased = $occupants->where('status', 'DECEASED')->count();

        $data = [
            'occupants' => $occupants->count(),
            'discharged' => $discharged,
            'vaccinated' => $vaccinated,
            'positive' => $positive,
            'deceased' => $deceased
        ];

        return response()->json($data);
    }
}
