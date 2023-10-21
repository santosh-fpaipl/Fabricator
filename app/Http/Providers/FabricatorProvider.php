<?php
namespace App\Http\Providers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Providers\Provider;
use App\Models\Fabricator;
use App\Http\Resources\FabricatorResource;
use App\Http\Requests\FabricatorAvailableRequest;
use App\Http\Responses\ApiResponse;

class FabricatorProvider extends Provider
{
    /**
    * Display a listing of the resource.
    */

    public function index(Request $request)
    {
        if (env('APP_DEBUG')) {
            Cache::forget('fabricators');
        }
        $fabricators = Cache::remember('fabricators', 24 * 60 * 60, function () {
            return Fabricator::with('user')->with('addresses')->get();
        });

        // viar = validInternalApiRequest
        $viar = $this->reqHasApiSecret($request);
        foreach ($fabricators as $fabricator) {
            if($viar){
                $fabricator->viar = true;
            }
        }

        return ApiResponse::success(FabricatorResource::collection($fabricators));
    }

    /**
     * Display the specified resource.
     */
    public function show(FabricatorAvailableRequest $request, Fabricator $fabricator)
    {
        if (env('APP_DEBUG')) {
            Cache::forget('fabricator');
        }
        if($request->has('check') && $request->check == 'available'){
            return ApiResponse::success(['available' => true]);
        }
        if(!$fabricator->active){
            return ApiResponse::error('Fabricator is inactive.', 404);
        }
        $fabricator = Cache::remember('fabricator'.$fabricator, 24 * 60 * 60, function () use($fabricator) {
            return $fabricator;
        });

        // viar = validInternalApiRequest
        $viar = $this->reqHasApiSecret($request);
        if($viar){
            $fabricator->viar = true;
        }

        return ApiResponse::success(new FabricatorResource($fabricator));
    }
   
}