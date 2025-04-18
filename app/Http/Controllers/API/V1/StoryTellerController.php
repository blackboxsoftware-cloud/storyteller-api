<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\StoryTeller;
use Illuminate\Http\Request;

class StoryTellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->get('per_page', 10);

        $storyTellers = StoryTeller::with('user')
                        ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $storyTellers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StoryTeller $storyTeller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StoryTeller $storyTeller)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoryTeller $storyTeller)
    {
        //
    }
}
