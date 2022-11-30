<?php

namespace App\Http\Controllers;

use App\Isolation;
use App\IsolationRoom;
use App\Http\Requests\IsolationRequest\DestroyIsolationRequest;
use App\Http\Requests\IsolationRequest\IndexIsolationRequest;
use App\Http\Requests\IsolationRequest\ShowIsolationRequest;
use App\Http\Requests\IsolationRequest\StoreIsolationRequest;
use App\Http\Requests\IsolationRequest\UpdateIsolationRequest;
use App\Http\Resources\IsolationResource;
use App\User;
use Illuminate\Http\Request;

class IsolationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexIsolationRequest $request)
    {
        //
        $isolation = Isolation::with('user')->get();

        return IsolationResource::collection($isolation);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIsolationRequest $request)
    {
        $user = User::create(array_merge($request->validated(), ['role' => 'ISOLATION']));
        $isolation = Isolation::create(array_merge(
            $request->validated(),
            ['user_id' => $user->id]
        ));
        return new IsolationResource($isolation);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(ShowIsolationRequest $request, int $isolation)
    {
        $isolation = Isolation::findOrFail($isolation);

        return new IsolationResource($isolation);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


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
    public function update(UpdateIsolationRequest $request, int $isolation)
    {
        $isolation = Isolation::findOrFail($isolation);
        $isolation->update($request->validated());

        return new IsolationResource($isolation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyIsolationRequest $request, int $isolation)
    {
        $isolation = Isolation::findOrFail($isolation);
        $isolation->delete();

        return null;
    }
}
