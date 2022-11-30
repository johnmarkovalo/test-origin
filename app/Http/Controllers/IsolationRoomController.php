<?php

namespace App\Http\Controllers;

use App\Isolation;
use App\IsolationRoom;
use App\Http\Requests\IsolationRoomRequest\DestroyIsolationRoomRequest;
use App\Http\Requests\IsolationRoomRequest\IndexIsolationRoomRequest;
use App\Http\Requests\IsolationRoomRequest\ShowIsolationRoomRequest;
use App\Http\Requests\IsolationRoomRequest\StoreIsolationRoomRequest;
use App\Http\Requests\IsolationRoomRequest\UpdateIsolationRoomRequest;
use App\Http\Resources\IsolationResource;
use App\Http\Resources\IsolationRoomResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsolationRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexIsolationRoomRequest $request)
    {
        //
        $isolationRoom = IsolationRoom::all();
        return IsolationRoomResource::collection($isolationRoom);
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
    public function store(StoreIsolationRoomRequest $request)
    {
        $user = Auth::user();
        $isolation = Isolation::whereUserId($user->id)->first();

        // dd($request->validated());
        $isolationRoom = IsolationRoom::create(
            array_merge([
                'isolation_id' => $isolation->id,
            ], $request->validated())
        );


        return new IsolationRoomResource($isolationRoom);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowIsolationRoomRequest $request, int $isolationRoom)
    {
        //
        $isolationRoom = IsolationRoom::findOrFail($isolationRoom);
        return new IsolationRoomResource($isolationRoom);
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
    public function update(UpdateIsolationRoomRequest $request, int $isolationRoom)
    {
        //
        $isolationRoom = IsolationRoom::findOrFail($isolationRoom);
        $isolationRoom->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyIsolationRoomRequest $request, int $isolationRoom)
    {
        //
        $isolationRoom = IsolationRoom::findOrFail($isolationRoom);
        $isolationRoom->delete();

        return null;
    }
}
