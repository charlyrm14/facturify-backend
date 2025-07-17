<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationsListRequest;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotificationController extends Controller
{
    /**
     * This PHP function retrieves notifications for a specific user based on optional type parameter
     * and returns a JSON response.
     * 
     * @param NotificationsListRequest request The `index` function is used to retrieve a list of
     * notifications for a specific user based on the provided request parameters.
     * 
     * @return JsonResponse The index function returns a JSON response containing the notifications
     * data for the authenticated user. If the user is not found, it returns an error response. It also
     * handles filtering notifications by type and pagination. If no notifications are found, it
     * returns a message indicating that no results were found. In case of any exceptions, it returns
     * an internal server error response.
     */
    public function index(NotificationsListRequest $request): JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $query = Notification::byUserId($user->id);
            
            if($request->has('type')) {
                $query->where('type', $request->type);
            }
            
            $notifications = $query->orderBy('created_at', 'desc')->paginate(15);

            if($notifications->total() === 0) {
                return response()->json(['message' => 'No results found'], 404);
            }

            return response()->json([
                'data' => $notifications
            ], 200);

        } catch (\Exception $e) {
            return response()->json(["error" => 'Internal server error'], 500);
        }
    }
}
