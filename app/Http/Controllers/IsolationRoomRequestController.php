<?php

namespace App\Http\Controllers;

use App\Isolation;
use App\IsolationRoom;
use App\Http\Requests\IsolationRoomRequestRequest\StoreIsolationRoomRequestRequest;
use App\Http\Requests\IsolationRoomRequestRequest\UpdateIsolationRoomRequestRequest;
use App\Http\Resources\IsolationRoomRequestResource;
use App\Occupant;
use App\IsolationRoomRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsolationRoomRequestController extends Controller
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
            $roomRequest = IsolationRoomRequest::all();
            return $roomRequest;
        } elseif ($authenticatedUser->role == 'ISOLATION') {
            $isolation = Isolation::where('user_id', $authenticatedUser->id)->first();
            // dd($isolation->id);
            $occupiedRoom = $isolation->isolationRooms->load("roomRequests");
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
    public function store(StoreIsolationRoomRequestRequest $request)
    {
        //
        // dd($request->input('isolation_room_id'));
        $roomRequest = IsolationRoomRequest::create(
            array_merge([
                'isolation_room_id' => $request->input('isolation_room_id'),
                'occupant_id' => $request->input('occupant_id'),
                'type' => $request->input('type'),
                'status' => $request->input('status')
            ])
        );

        $updateRoom = IsolationRoom::where('id', $request->input('isolation_room_id'))->update(array('status' => 'OCCUPIED'));


        return new IsolationRoomRequestResource($roomRequest);
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
    public function update(UpdateIsolationRoomRequestRequest $request, IsolationRoomRequest $roomRequest)
    {
        //
        $roomRequest = IsolationRoomRequest::findOrFail($roomRequest);
        $roomRequest->update($request->validated());

        $occupant = Occupant::findOrFail($roomRequest->occupant_id);
        $occupant->update($request->validated());

        return new IsolationRoomRequestResource($roomRequest);
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
