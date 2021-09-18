<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Repositories\ResponseRepository;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Repositories\ProfileRepository;

class ProfileController extends Controller
{

    public $authRepository;
    public $responseRepository;

    public function __construct(ProfileRepository $profileReporsitory, ResponseRepository $responseRepository)
    {
        $this->profileReporsitory = $profileReporsitory;
        $this->responseRepository = $responseRepository;
    }

    public function addProfile(ProfileRequest $request)
    {
        try{
            $data = $this->profileReporsitory->profileAdd($request->all());
            return $this->responseRepository->ResponseSuccess($data, 'Profile Added Successfully');
        } catch (\Exception $e) {
            return $this->responseRepository->ResponseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Edit Profile
     */

    public function editProfile(Request $request)
    {
        $id=$request->id;
        try{
            $data = $this->profileReporsitory->profileEdit($request->all(),$id);
            return $this->responseRepository->ResponseSuccess($data, 'Profile Edited Successfully');
        } catch (\Exception $e) {
            return $this->responseRepository->ResponseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     /**
     * Edit Profile
     */

    public function deleteProfile(Request $request)
    {
        $id=$request->id;
        try{
            $data = $this->profileReporsitory->profileDelete($id);
            return $this->responseRepository->ResponseSuccess($data, 'Profile Deleted Successfully');
        } catch (\Exception $e) {
            return $this->responseRepository->ResponseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
