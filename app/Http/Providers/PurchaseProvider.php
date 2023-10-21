<?php
namespace App\Http\Providers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Http\Providers\Provider;
use App\Models\Purchase;
use App\Http\Requests\PurchaseCreateRequest;
use App\Http\Requests\PurchaseAvailableRequest;
use App\Http\Resources\PurchaseResource;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;

class PurchaseProvider extends Provider
{

    /**
    * Display a listing of the resource.
    */
    public function index(Request $request)
    {
        if (env('APP_DEBUG')) {
            Cache::forget('purchases');
        }
        $purchases = Cache::remember('purchases', 24 * 60 * 60, function () {
            return Purchase::get();
        });

        // viar = validInternalApiRequest
        $viar = $this->reqHasApiSecret($request);
        foreach ($purchases as $purchase) {
            if($viar){
                $purchase->viar = true;
            }
        }

        return ApiResponse::success(PurchaseResource::collection($purchases));
    }

    public function store(PurchaseCreateRequest $request){
        //$so_sids = '{"0":"MC-SO1023-0001"}';
        DB::beginTransaction();
        try{
            $purchase = Purchase::create([
                'po_sid' => $request->po_sid,
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

        // viar = validInternalApiRequest
        $viar = $this->reqHasApiSecret($request);
        if($viar){
            $purchase->viar = true;
        }

        return ApiResponse::success(new PurchaseResource($purchase));
    }

   public function show(PurchaseAvailableRequest $request, Purchase $purchase){
        if (env('APP_DEBUG')) {
            Cache::forget('purchase');
        }
        $purchase = Cache::remember('purchase', 24 * 60 * 60, function () use($purchase) {
            return $purchase;
        });
        if($request->has('check') && $request->check == 'available'){
            return ApiResponse::success(['available' => true]);
        }

        // viar = validInternalApiRequest
        $viar = $this->reqHasApiSecret($request);
        if($viar){
            $purchase->viar = true;
        }
        
        return ApiResponse::success(new PurchaseResource($purchase));
   }
   
}