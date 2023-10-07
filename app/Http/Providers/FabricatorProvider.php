<?php
namespace App\Http\Providers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Providers\Provider;
use App\Models\Fabricator;
use App\Http\Resources\FabricatorResource;
use App\Http\Responses\ApiResponse;

class FabricatorProvider extends Provider
{
    /**
    * Display a listing of the resource.
    */

    public function index()
    {
        Cache::forget('fabricators');
        $fabricators = Cache::remember('fabricators', 24 * 60 * 60, function () {
            return Fabricator::with('user')->with('addresses')->get();
        });
        return ApiResponse::success(FabricatorResource::collection($fabricators));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Fabricator $fabricator)
    {
        if(!$fabricator->active){
            return ApiResponse::error('Fabricator is inactive.',404);
        }
        Cache::forget('fabricator'.$fabricator);
        $fabricator = Cache::remember('fabricator'.$fabricator, 24 * 60 * 60, function () use($fabricator) {
            return $fabricator;
        });
        return ApiResponse::success(new FabricatorResource($fabricator));
    }
   
}