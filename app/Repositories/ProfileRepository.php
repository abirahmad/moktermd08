<?php

namespace App\Repositories;

use App\Models\Profile;

class ProfileRepository
{
    /**
     * Profile Add
     */

    public function profileAdd($request)
    {
        $profile = new Profile();
        $profile->full_name = $request->full_name;
        $profile->address = $request->address;
        $profile->email = $request->email;
        $profile->save();

        return true;
    }

     /**
     * Profile update
     */

    public function profileEdit($request,$id)
    {
        $profile = Profile::findOrFail($id);
        $profile->full_name = $request->full_name;
        $profile->address = $request->address;
        $profile->email = $request->email;
        $profile->save();

        return true;
    }

     /**
     * Profile delete
     */

    public function profileDelete($id)
    {
        $profile = Profile::findOrFail($id);
        if(!is_null($profile)){
            $profile->delete();
        }

        return true;
    }
}
