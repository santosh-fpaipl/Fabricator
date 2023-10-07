<?php
namespace App\Http\Providers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Providers\Provider;
use App\Models\Purchase;
use App\Http\Requests\PurchaseCreateRequest;
use App\Http\Resources\PurchaseResource;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;

class PurchaseProvider extends Provider
{

    /**
    * Display a listing of the resource.
    */

    public function index()
    {
        Cache::forget('purchases');
        $purchases = Cache::remember('purchases', 24 * 60 * 60, function () {
            return Purchase::get();
        });
        return ApiResponse::success(PurchaseResource::collection($purchases));
    }

    public function store(PurchaseCreateRequest $request){
        DB::beginTransaction();
        try{
            $purchase = Purchase::create([
                'jwo_sid' => $request->jwo_sid,
            ]);
            foreach(json_decode($request->so_sids, true) as $so_sid){

                $response = Http::put('http://192.168.1.133:8003/api/saleorders/'.$so_sid, [
                    'pending' => 0,
                ]);
            }
           

            Log::info($request);

            DB::commit();
        } catch(\Exception $e){
            DB::rollBack();
            return ApiResponse::error($e->getMessage(),404);
        }
        return ApiResponse::success(new PurchaseResource($purchase));
    }

   public function show(Request $request, Purchase $purchase){
        Cache::forget('purchase');
        $purchase = Cache::remember('purchase', 24 * 60 * 60, function () use($purchase) {
            return $purchase;
        });

        return ApiResponse::success(new PurchaseResource($purchase));

   }

   public function checkHasPurchase(Request $request){

        if(!Purchase::where('jwo_sid', $request->jwo_sid)->exists()){
            return ApiResponse::error('Jobworkorder does not exist.', 404);
        }
        Cache::forget('checkHasPurchase');
        $purchase = Cache::remember('checkHasPurchase', 24 * 60 * 60, function () use($request) {
            return Purchase::where('jwo_sid', $request->jwo_sid)->first();
        });

        return ApiResponse::success(new PurchaseResource($purchase));

    }
   
}