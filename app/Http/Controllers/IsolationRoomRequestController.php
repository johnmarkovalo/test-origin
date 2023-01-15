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
            $roomRequests = $isolation->roomRequests();

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
        if (Auth::user()->role == 'ISOLATION') {
            $isolation = Isolation::where('user_id', Auth::user()->id)->first();
            $request->merge(['isolation_id' => $isolation->id]);
        } elseif (Auth::user()->role == 'OCCUPANT') {
            $occupant = Occupant::where('user_id', Auth::user()->id)->first();
            $request->merge(['occupant_id' => $occupant->id]);
            $type = $occupant->type;
            $request->merge(['type' => $type]);
            $status = 'PENDING';
            $request->merge(['status' => $status]);
        } elseif (Auth::user()->role == 'ADMINISTRATOR') {
            $isolationRoom = IsolationRoom::where('id', $request->input('isolation_room_id'))->first();
            $request->merge(['isolation_id' => $isolationRoom->isolation_id]);
        }
        $roomRequest = IsolationRoomRequest::create(
            array_merge([
                'isolation_id' => $request->input('isolation_id'),
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
    public function update(UpdateIsolationRoomRequestRequest $request, $id)
    {
        $roomRequest = IsolationRoomRequest::where('id', $id)->first();
        $roomRequest->update($request->all());
        if ($request->input('status') == 'DISCHARGED') {
            $updateRoom = IsolationRoom::where('id', $roomRequest->isolation_room_id)->update(array('status' => 'VACANT'));
        } else {
            $updateRoom = IsolationRoom::where('id', $roomRequest->isolation_room_id)->update(array('status' => 'OCCUPIED'));
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
