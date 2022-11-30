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
            // dd($hospital->id);
            $occupiedRoom = $hospital->hospitalRooms->load("roomRequests");
            //Merge all the room requests of each room into one array
            $roomRequests = [];
            foreach ($occupiedRoom as $room) {
                foreach ($room->roomRequests as $request) {
                    array_push($roomRequests, $request);
                }
            }

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
        //
        // dd($request->input('hospital_room_id'));
        $roomRequest = RoomRequest::create(
            array_merge([
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
    public function update(UpdateRoomRequestRequest $request, RoomRequest $roomRequest)
    {
        //
        $roomRequest = RoomRequest::findOrFail($roomRequest);
        $roomRequest->update($request->validated());

        $occupant = Occupant::findOrFail($roomRequest->occupant_id);
        $occupant->update($request->validated());

        return new RoomRequestResource($roomRequest);
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
