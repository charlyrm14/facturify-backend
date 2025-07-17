<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ThreadDetailRequest;
use App\Http\Requests\ThreadRequest;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class ConversationController extends Controller
{
    /**
     * This PHP function retrieves a list of threads based on specified criteria and returns the result
     * as a JSON response.
     *
     * @param ThreadRequest request The `threadsList` function accepts a `ThreadRequest` object as a
     * parameter. This object likely contains information about the request made to the function, such
     * as the type of conversation to filter by and any search terms provided.
     *
     * @return JsonResponse a JSON response. If there are conversations found based on the query
     * parameters, it will return a JSON response with the conversations data along with a status code
     * of 200. If no conversations are found, it will return a JSON response with a message indicating
     * "No results found" and a status code of 404. If an exception occurs during the process, it will
     * return a JSON response
     */
    public function threadsList(ThreadRequest $request): JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $query = Conversation::query();

            if($request->type) {
                $query->where('conversation_type_id', $request->type);
            }
            
            $search = $request->search;
            if ($search) {
                $query->whereHas('conversationType', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            $conversations = $query->with(['conversationType', 'user'])->paginate();

            if($conversations->total() === 0) {
                return response()->json(['message' => 'No results found'], 404);
            }

            return response()->json([
                'data' => $conversations
            ], 200);

        } catch (\Exception $e) {
            return response()->json(["error" => 'Internal server error'], 500);
        }
    }

    /**
     * The function `threadDetail` retrieves a conversation with its related conversation type and user
     * by its ID and returns it as JSON response, handling errors appropriately.
     *
     * @param ThreadDetailRequest request The `threadDetail` function takes two parameters:
     * @param int thread_id The `thread_id` parameter in the `threadDetail` function represents the
     * unique identifier of the thread or conversation for which you want to retrieve details. It is
     * used to fetch the conversation details from the database based on this specific identifier.
     *
     * @return The `threadDetail` function is returning a JSON response. If the conversation with the
     * specified thread ID is found, it returns a JSON response with the conversation data under the
     * 'data' key and a status code of 200. If the conversation is not found, it returns a JSON
     * response with a 'Resource not found' message and a status code of 404. If an exception occurs
     * during the
     */
    public function threadDetail(ThreadDetailRequest $request, int $thread_id)
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            $conversation = Conversation::with(['conversationType', 'user'])->byId($thread_id)->first();

            if(!$conversation) {
                return response()->json(['message' => 'Resource not found'], 404);
            }

            return response()->json([
                'data' => $conversation
            ], 200);

        } catch (\Exception $e) {
            return response()->json(["error" => 'Internal server error'], 500);
        }
    }
}
