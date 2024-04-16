<?php

namespace App\Http\Controllers\Api;

use App\Events\RealTimeEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

/**
 * This controller will handle all the related triggers for event broadcasting.
 */
class RealTimeController extends Controller
{

    /**
     * This event will trigger the broadcast.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function realtimeTestEvent(Request $request): JsonResponse
    {
        $message = $request->input('message', 'Default message');

        $recentMessage = Redis::connection()->get('recent_message');

        if($recentMessage != $message){
            $recentMessage = Redis::connection()->set('recent_message', (string) $message);
        }
        event(new RealTimeEvent($recentMessage));
        return response()->json(['message' => 'Event triggered ' . $recentMessage]);
    }
}
