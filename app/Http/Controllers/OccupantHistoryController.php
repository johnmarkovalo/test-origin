<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OccupantHistory;

class OccupantHistoryController extends Controller
{
    public function index()
    {
        $occupantHistory = OccupantHistory::with('occupant')->get();
        return $occupantHistory;
    }

    public function show($id)
    {
        $occupantHistory = OccupantHistory::with('occupant')->find($id);
        return $occupantHistory;
    }

    public function store(Request $request, $occupant_id)
    {
        $occupantHistory = OccupantHistory::create(
            array_merge($request->all(), ['occupant_id' => $occupant_id])
        );
        return $occupantHistory;
    }

    public function update(Request $request, $occupant_id, $id)
    {
        $occupantHistory = OccupantHistory::find($id);
        $occupantHistory->update($request->all());
        return $occupantHistory;
    }
}
