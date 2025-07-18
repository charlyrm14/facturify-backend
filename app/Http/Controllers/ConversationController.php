<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ReplyMessageRequest;
use App\Http\Requests\StoreThreadRequest;
use App\Http\Requests\ThreadDetailRequest;
use App\Http\Requests\ThreadRequest;
use App\Models\Conversation;
use App\Models\ConversationUser;
use App\Models\Message;
use App\Models\Notification;
use App\Resources\MessageResource;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

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
    public function index(ThreadRequest $request): JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $query = Conversation::byUserId($user->id);

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
    public function threadDetail(ThreadDetailRequest $request, int $thread_id): JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            $conversation = Conversation::with([
                'conversationType', 
                'user', 
                'messages'
            ])->byId($thread_id)->first();

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

    /**
     * The function `createThread` creates a new conversation thread with an initial message and
     * returns a JSON response.
     * 
     * @param StoreThreadRequest request The `createThread` function is responsible for creating a new
     * thread in a conversation along with an initial message. It follows a transactional approach to
     * ensure data consistency. Here's a breakdown of the code:
     * 
     * @return JsonResponse The `createThread` function returns a JSON response. If the thread creation
     * is successful, it returns a JSON response with a status code of 201 (Created) containing a
     * success message and the created conversation data. If an exception occurs during the process, it
     * returns a JSON response with a status code of 500 (Internal Server Error) indicating an internal
     * server error.
     */
    public function createThread(StoreThreadRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            
            $data = $request->validated();
            $conversation = Conversation::createConversation($data);
            ConversationUser::createConversationDetail($conversation);
            $message = Message::createInitialMessage($conversation, $data);
                
            DB::commit();

            $conversation->message = $message;

            return response()->json([
                'message' => 'Thread created successfully',
                'data' => $conversation
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => 'Internal server error'], 500);
        }
    }

    /**
     * This PHP function replies to a message in a conversation thread and returns a JSON response with
     * the result.
     * 
     * @param ReplyMessageRequest request The `replyMessage` function takes two parameters:
     * @param int thread_id The `thread_id` parameter in the `replyMessage` function is used to
     * identify the conversation thread to which the reply message will be added. It is an integer
     * value that helps in locating the specific conversation thread in the database.
     * 
     * @return The `replyMessage` function returns a JSON response with a success message and the data
     * of the newly created message if the operation is successful. If there is an error during the
     * process, it returns a JSON response with an error message indicating an internal server error.
     */
    public function replyMessage(ReplyMessageRequest $request, int $thread_id): JsonResponse
    {
        DB::beginTransaction();

        try {

            $conversation = Conversation::byId($thread_id)->first();

            if (!$conversation) {
                return response()->json(['message' => 'Resource not found'], 404);
            }

            $message = Message::createReplyMessage($conversation, $request->validated());
            Notification::createNotificationReplyMessage($conversation->user_id, $message);

            DB::commit();

            $message->load(['conversation', 'user', 'parentMessage']);

            return response()->json([
                'message' => 'Message send succesfully',
                'data' => new MessageResource($message)
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => 'Internal server error'], 500);
        }
    }
}
