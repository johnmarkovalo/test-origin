<?php

namespace App\Http\Controllers;

use App\Hospital;
use App\HospitalRoom;
use App\Http\Requests\RoomRequestRequest\StoreRoomRequestRequest;
use App\Http\Requests\RoomRequestRequest\UpdateRoomRequestRequest;
use App\Http\Resources\RoomRequestResource;
use App\Occupant;
use App\RoomRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->role == 'ADMINISTRATOR') {
            $roomRequest = RoomRequest::all();
            return $roomRequest;
        } elseif ($authenticatedUser->role == 'HOSPITAL') {
            $hospital = Hospital::where('user_id', $authenticatedUser->id)->first();
            $roomRequests = $hospital->roomRequests();

            return $roomRequests;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoomRequestRequest $request)
    {
        if (Auth::user()->role == 'HOSPITAL') {
            $hospital = Hospital::where('user_id', Auth::user()->id)->first();
            $request->merge(['hospital_id' => $hospital->id]);
        } elseif (Auth::user()->role == 'OCCUPANT') {
            $occupant = Occupant::where('user_id', Auth::user()->id)->first();
            $request->merge(['occupant_id' => $occupant->id]);
            $type = $occupant->type;
            $request->merge(['type' => $type]);
            $status = 'PENDING';
            $request->merge(['status' => $status]);
        } elseif (Auth::user()->role == 'ADMINISTRATOR') {
            $hospitalRoom = HospitalRoom::where('id', $request->input('hospital_room_id'))->first();
            $request->merge(['hospital_id' => $hospitalRoom->hospital_id]);
        }
        $roomRequest = RoomRequest::create(
            array_merge([
                'hospital_id' => $request->input('hospital_id'),
                'hospital_room_id' => $request->input('hospital_room_id'),
                'occupant_id' => $request->input('occupant_id'),
                'type' => $request->input('type'),
                'status' => $request->input('status')
            ])
        );

        $updateRoom = HospitalRoom::where('id', $request->input('hospital_room_id'))->update(array('status' => 'OCCUPIED'));


        return new RoomRequestResource($roomRequest);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoomRequestRequest $request, $id)
    {
        $roomRequest = RoomRequest::where('id', $id)->first();
        $roomRequest->update($request->all());
        if ($request->input('status') == 'DISCHARGED') {
            $updateRoom = HospitalRoom::where('id', $roomRequest->hospital_room_id)->update(array('status' => 'VACANT'));
        } else {
            $updateRoom = HospitalRoom::where('id', $roomRequest->hospital_room_id)->update(array('status' => 'OCCUPIED'));
        }
        return $roomRequest;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
