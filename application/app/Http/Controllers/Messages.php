<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Messages\FeedResponse;
use App\Http\Responses\Messages\IndexResponse;
use App\Repositories\FileRepository;
use App\Repositories\MessageRepository;
use App\Rules\NoTags;
use DB;
use Validator;
use App\Models\PushToken;

class Messages extends Controller {

    /**
     * The message repository instance.
     */
    protected $messagerepo;

    public function __construct(MessageRepository $messagerepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //route middleware
        $this->middleware('messagesMiddlewareIndex')->only([
            'index',
            'storeText',
            'storeFiles',
        ]);

        //route middleware
        $this->middleware('messagesMiddlewareCreate')->only([
            'storeText',
            'storeFiles',
        ]);

        //route middleware
        $this->middleware('messagesMiddlewareDestroy')->only([
            'destroy',
        ]);

        $this->messagerepo = $messagerepo;
    }
    /**
     * Display a listing of messages
     * @return blade view | ajax view
     */
    public function index() {

        //get team members
        $users = \App\Models\User::leftJoin('messages', function ($join) {
                $join->on('users.unique_id', '=', 'messages.message_source')
                     ->where('messages.message_created', '=', function ($query) {
                         $query->select(DB::raw('MAX(message_created)'))
                               ->from('messages')
                               ->Where(function($subQueryx) {
                                    $subQueryx->whereColumn('message_source', 'users.unique_id')
                                             ->where('message_target', auth()->user()->unique_id);
                                });
                                
                     });
            })->Where('type', 'team')->Where('status', 'active')->orderBy('message_created', 'desc')->get();
            
            /*echo \App\Models\User::leftJoin('messages', function ($join) {
                $join->on('users.unique_id', '=', 'messages.message_source')
                     ->where('messages.message_created', '=', function ($query) {
                         $query->select(DB::raw('MAX(message_created)'))
                               ->from('messages')
                               ->where(function($subQuery) {
                                    $subQuery->where('message_source', auth()->user()->unique_id)
                                             ->whereColumn('message_target', 'users.unique_id');
                                })
                                ->orWhere(function($subQuery) {
                                    $subQuery->whereColumn('message_source', 'users.unique_id')
                                             ->where('message_target', auth()->user()->unique_id);
                                });
                     });
            })->Where('type', 'team')->Where('status', 'active')->orderBy('message_created', 'desc')->toSql();
            */
//select * from `users` left join `messages` on `users`.`unique_id` = `messages`.`message_source` and `messages`.`message_created` = (select MAX(message_created) from `messages` where (`message_source` = '65b22498936b03.10824782' and `message_target` = `users`.`unique_id`) or (`message_source` = `users`.`unique_id` and `message_target` = '65b22498936b03.10824782')) where `type` = 'team' and `status` = 'active' order by `message_created` desc;

        //reponse payload
        
        $payload = [
            'page' => $this->pageSettings('index'),
            'users' => $users,
        ];
//print_r($payload);
        //show the view
        return new IndexResponse($payload);
    }

    /**
     * get the messages feed
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getFeed() {

        //check if file exists in the database
        $data = [
            'apply_filters' => true,
            'message_source' => request('message_source'),
            'message_target' => request('message_target'),
            'timestamp' => request('timestamp'),
        ];
        $messages = $this->messagerepo->search('', $data);

        //revese order
        //$messages = $messages->reverse();

        //delete all the users 'unread' for this feed
        \App\Models\MessagesTracking::Where('messagestracking_target', request('message_target'))
            ->Where('messagestracking_user_unique_id', auth()->user()->unique_id)
            ->Where('messagestracking_type', 'read')
            ->delete();

        //reponse payload
        $payload = [
            'messages' => $messages,
            'timestamp' => time(),
            'message_counters' => $this->countMessages(),
            'deleted_messages' => $this->getDeletedMessages(),
            'feed_source' => 'polling',
            'user_status' => $this->getUserStatus(),
        ];

        //show the feed
        return new FeedResponse($payload);

    }


    

    /**
     * store text message
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeText() {

        //validate
        $validator = Validator::make(request()->all(), [
            'message_source' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        if ($value != auth()->user()->unique_id) {
                            return $fail('1');
                        }
                    }
                },
                new NoTags,
            ],
            'message_target' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value && ($value != 'team' && $value != 'team2' && $value != 'team3')) {
                        if (\App\Models\User::Where('unique_id', $value)->doesntExist()) {
                            return $fail('2');
                        }
                    }
                },
                new NoTags,
            ],
            'message_text' => [
                'required',
            ],
        ]);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }
            abort(409);
        }

        //save message
        $message = new \App\Models\Message();
        $message->message_creatorid = auth()->id();
        $message->message_creator_uniqueid = auth()->user()->unique_id;
        $message->message_unique_id = str_unique();
        $message->message_timestamp = time();
        $message->message_source = request('message_source');
        $message->message_target = request('message_target');
        $message->message_text = request('message_text');
        $message->message_parent_id = request('message_parent_id');
        $message->message_type = 'text';
        $message->save();
        
        $user_receiver_id = \App\Models\User::Where('unique_id', request('message_target'))->first()->id;

        $pushToken = PushToken::where('user_id', $user_receiver_id)->first()->push_token;
        
        if($pushToken != null){
            $credentialsFilePath = './task-meister-e16df-firebase-adminsdk-f0bxw-aa9849f52a.json'; //replace this with your actual path and file name
            $client = new \Google_Client();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $token = $client->getAccessToken();
            $tokenx = $token['access_token'];
            
            $apiurl = 'https://fcm.googleapis.com/v1/projects/task-meister-e16df/messages:send';   //replace "your-project-id" with...your project ID
            
            $headers = [
                 'Authorization: Bearer ' . $tokenx,
                 'Content-Type: application/json'
            ];
            
            $notification_tray = [
                 'title'             => "New Message",
                 'body'              => strlen(request('message_text')) > 40 ? substr(request('message_text'),0,40)."..." : request('message_text'),
                 'image'             => "https://cdn.shopify.com/s/files/1/1061/1924/files/Sunglasses_Emoji.png?2976903553660223024",
            ];
                 
            $link = 'https://www.task-meister.com/messages?target='.messagesCounterUniqueID(request('message_source'));
            
                 
            $notification_webpush = [
                    'fcm_options'   =>  [
                            'link'  =>  $link,//'https://www.task-meister.com/projects/'.$eventtracking->resource_id,
                        ]
                    ];

             //The $in_app_module array above can be empty - I use this to send variables in to my app when it is opened, so the user sees a popup module with the message additional to the generic task tray notification.
            $messagex = [
                'message' => [
                     'token' => $pushToken,
                     'notification'     => $notification_tray,
                     'webpush'          => $notification_webpush,
                 ],
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiurl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messagex));
            //print_r($message);
            $result = curl_exec($ch);
            //print_r($result);
            if ($result === FALSE) {
              //Failed
              die('Curl failed: ' . curl_error($ch));
            }
            
            curl_close($ch);
            // end push notification
        }
        
        //record tracking
        if (request('message_target') == 'team') {
            $users = \App\Models\User::Where('type', 'team')->Where('status', 'active')->WhereNotIn('id', [auth()->id()])->get();
            foreach ($users as $user) {
                $tracking = new \App\Models\MessagesTracking();
                $tracking->messagestracking_massage_unique_id = $message->message_unique_id;
                $tracking->messagestracking_user_unique_id = $user->unique_id;
                $tracking->messagestracking_target = 'team';
                $tracking->messagestracking_type = 'read';
                $tracking->save();
            }
        } elseif (request('message_target') == 'team2') {
            $users = \App\Models\User::Where('type', 'team')->Where('status', 'active')->WhereNotIn('id', [auth()->id()])->get();
            foreach ($users as $user) {
                if($user->id == 1 || $user->id == 2 || $user->id == 9 || $user->id == 14 || $user->id == 15 || $user->id == 16 ) {
                    $tracking = new \App\Models\MessagesTracking();
                    $tracking->messagestracking_massage_unique_id = $message->message_unique_id;
                    $tracking->messagestracking_user_unique_id = $user->unique_id;
                    $tracking->messagestracking_target = 'team2';
                    $tracking->messagestracking_type = 'read';
                    $tracking->save();
                }
            }
        } elseif (request('message_target') == 'team3') {
            $users = \App\Models\User::Where('type', 'team')->Where('status', 'active')->WhereNotIn('id', [auth()->id()])->get();
            foreach ($users as $user) {
                if($user->id == 1 || $user->id == 2 || $user->id == 15 || $user->id == 24 || $user->id == 34) {
                    $tracking = new \App\Models\MessagesTracking();
                    $tracking->messagestracking_massage_unique_id = $message->message_unique_id;
                    $tracking->messagestracking_user_unique_id = $user->unique_id;
                    $tracking->messagestracking_target = 'team3';
                    $tracking->messagestracking_type = 'read';
                    $tracking->save();
                }
            }
        } else {
            $tracking = new \App\Models\MessagesTracking();
            $tracking->messagestracking_massage_unique_id = $message->message_unique_id;
            $tracking->messagestracking_user_unique_id = request('message_target');
            $tracking->messagestracking_target = request('message_source');
            $tracking->messagestracking_type = 'read';
            $tracking->save();
        }

        //delete all the users 'unread' for this feed
        \App\Models\MessagesTracking::Where('messagestracking_target', request('message_target'))
            ->Where('messagestracking_user_unique_id', auth()->user()->unique_id)
            ->Where('messagestracking_type', 'read')
            ->delete();

        //get this message and any others based on timestamp
        $data = [
            'apply_filters' => true,
            'message_source' => request('message_source'),
            'message_target' => request('message_target'),
            'timestamp' => request('timestamp'),
        ];
        $messages = $this->messagerepo->search('', $data);

        //reponse payload
        $payload = [
            'timestamp' => $message->message_timestamp,
            'messages' => $messages,
            'message_counters' => $this->countMessages(),
            'deleted_messages' => $this->getDeletedMessages(),
            'feed_source' => 'posting',
            'user_status' => $this->getUserStatus(),
        ];

        //show the feed
        return new FeedResponse($payload);

    }

    /**
     * delete a message
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        //get the record
        $message = \App\Models\Message::Where('message_unique_id', $id)->first();

        //validate

        if ($message->message_target == 'team') {
            $users = \App\Models\User::Where('type', 'team')->Where('status', 'active')->WhereNotIn('id', [auth()->id()])->get();
            foreach ($users as $user) {
                $tracking = new \App\Models\MessagesTracking();
                $tracking->messagestracking_user_unique_id = $user->unique_id;
                $tracking->messagestracking_target = $message->message_unique_id;
                $tracking->messagestracking_type = 'delete';
                $tracking->save();
            }
        } elseif ($message->message_target == 'team2') {
            $users = \App\Models\User::Where('type', 'team')->Where('status', 'active')->WhereNotIn('id', [auth()->id()])->get();
            foreach ($users as $user) {
                if($user->id == 1 || $user->id == 2 || $user->id == 9 || $user->id == 14 || $user->id == 15 || $user->id == 16 ) {
                    $tracking = new \App\Models\MessagesTracking();
                    $tracking->messagestracking_user_unique_id = $user->unique_id;
                    $tracking->messagestracking_target = $message->message_unique_id;
                    $tracking->messagestracking_type = 'delete';
                    $tracking->save();
                }
            }
        } elseif ($message->message_target == 'team3') {
            $users = \App\Models\User::Where('type', 'team')->Where('status', 'active')->WhereNotIn('id', [auth()->id()])->get();
            foreach ($users as $user) {
                if($user->id == 1 || $user->id == 2 || $user->id == 15 || $user->id == 24 || $user->id == 34 ) {
                    $tracking = new \App\Models\MessagesTracking();
                    $tracking->messagestracking_user_unique_id = $user->unique_id;
                    $tracking->messagestracking_target = $message->message_unique_id;
                    $tracking->messagestracking_type = 'delete';
                    $tracking->save();
                }
            }
        } else {
            //record tracking
            $tracking = new \App\Models\MessagesTracking();
            $tracking->messagestracking_user_unique_id = $message->message_target;
            $tracking->messagestracking_target = $message->message_unique_id;
            $tracking->messagestracking_type = 'delete';
            $tracking->save();
        }

        //messages delete by admin (also delete for the sender)
        if (auth()->user()->unique_id != $message->message_creator_uniqueid) {
            $tracking = new \App\Models\MessagesTracking();
            $tracking->messagestracking_user_unique_id = $message->message_creator_uniqueid;
            $tracking->messagestracking_target = $message->message_unique_id;
            $tracking->messagestracking_type = 'delete';
            $tracking->save();
        }

        //delete unread tracking
        \App\Models\MessagesTracking::Where('messagestracking_massage_unique_id', $message->message_unique_id)->delete();

        //delete record
        $message->delete();

        //response
        return response()->json([]);

    }

    /**
     * count a users unread messages and return an array
     * @return array
     */
    public function countMessages() {

        //count records
        $message_count = \App\Models\MessagesTracking::where('messagestracking_user_unique_id', auth()->user()->unique_id)
            ->Where('messagestracking_type', 'read')
            ->select(DB::raw('messagestracking_target, count(*) as counter'))
            ->groupBy('messagestracking_target')
            ->get();

        //return count
        return $message_count;

    }

    /**
     * count a users unread messages and return an array
     * @return array
     */
    public function getDeletedMessages() {

        $list = [];

        //count records
        $messages = \App\Models\MessagesTracking::where('messagestracking_user_unique_id', auth()->user()->unique_id)
            ->Where('messagestracking_type', 'delete')
            ->get();

        //create a list
        foreach ($messages as $message) {
            $list[] = $message->messagestracking_target;
        }

        //delete records
        $messages = \App\Models\MessagesTracking::where('messagestracking_user_unique_id', auth()->user()->unique_id)
            ->Where('messagestracking_type', 'delete')
            ->delete();

        //return count
        return $list;

    }

    /**
     * store files
     *
     * @return \Illuminate\Http\Response
     */
    public function storeFiles(FileRepository $filerepo) {

        //validate
        if (!request()->filled('attachments')) {
            abort(409, __('lang.no_files_selected'));
        }

        //timestamp
        $timestamp = time();

        foreach (request('attachments') as $uniqueid => $file_name) {

            if (!$file_type = $filerepo->processGeneralUpload([
                'directory' => $uniqueid,
                'filename' => $file_name,
            ])) {
                //skip this file
                continue;
            }

            //save message
            $message = new \App\Models\Message();
            $message->message_creatorid = auth()->id();
            $message->message_unique_id = str_unique();
            $message->message_creator_uniqueid = auth()->user()->unique_id;
            $message->message_timestamp = $timestamp;
            $message->message_source = request('message_source');
            $message->message_target = request('message_target');
            $message->message_file_name = $file_name;
            $message->message_file_thumb_name = generateThumbnailName($file_name);
            $message->message_file_directory = $uniqueid;
            $message->message_file_type = $file_type;
            $message->message_type = 'file';
            $message->save();

            //record tracking
            if (request('message_target') == 'team') {
                $users = \App\Models\User::Where('type', 'team')->Where('status', 'active')->WhereNotIn('id', [auth()->id()])->get();
                foreach ($users as $user) {
                    $tracking = new \App\Models\MessagesTracking();
                    $tracking->messagestracking_user_unique_id = $user->unique_id;
                    $tracking->messagestracking_target = 'team';
                    $tracking->messagestracking_type = 'read';
                    $tracking->save();
                }
            } elseif (request('message_target') == 'team2') {
                $users = \App\Models\User::Where('type', 'team')->Where('status', 'active')->WhereNotIn('id', [auth()->id()])->get();
                foreach ($users as $user) {
                    if($user->id == 1 || $user->id == 2 || $user->id == 9 || $user->id == 14 || $user->id == 15 || $user->id == 16 ) {
                        $tracking = new \App\Models\MessagesTracking();
                        $tracking->messagestracking_user_unique_id = $user->unique_id;
                        $tracking->messagestracking_target = 'team';
                        $tracking->messagestracking_type = 'read';
                        $tracking->save();
                    }
                }
            } elseif (request('message_target') == 'team3') {
                $users = \App\Models\User::Where('type', 'team')->Where('status', 'active')->WhereNotIn('id', [auth()->id()])->get();
                foreach ($users as $user) {
                    if($user->id == 1 || $user->id == 2 || $user->id == 15 || $user->id == 24 || $user->id == 34 ) {
                        $tracking = new \App\Models\MessagesTracking();
                        $tracking->messagestracking_user_unique_id = $user->unique_id;
                        $tracking->messagestracking_target = 'team';
                        $tracking->messagestracking_type = 'read';
                        $tracking->save();
                    }
                }
            } else {
                $tracking = new \App\Models\MessagesTracking();
                $tracking->messagestracking_user_unique_id = request('message_target');
                $tracking->messagestracking_target = request('message_source');
                $tracking->messagestracking_type = 'read';
                $tracking->save();
            }

        }

        //delete all the users 'unread' for this feed
        \App\Models\MessagesTracking::Where('messagestracking_target', request('message_target'))
            ->Where('messagestracking_user_unique_id', auth()->user()->unique_id)
            ->Where('messagestracking_type', 'read')
            ->delete();

        //get this message and any others based on timestamp
        $data = [
            'apply_filters' => true,
            'message_source' => request('message_source'),
            'message_target' => request('message_target'),
            'timestamp' => request('timestamp'),
        ];
        $messages = $this->messagerepo->search('', $data);

        //reponse payload
        $payload = [
            'timestamp' => $timestamp,
            'messages' => $messages,
            'message_counters' => $this->countMessages(),
            'deleted_messages' => $this->getDeletedMessages(),
            'feed_source' => 'posting-files',
            'user_status' => $this->getUserStatus(),
        ];

        //show the feed
        return new FeedResponse($payload);
    }

    /**
     * get each users current online status
     * @return array
     */
    public function getUserStatus() {

        $list = [];

        //get team members
        $users = \App\Models\User::Where('type', 'team')->Where('status', 'active')->orderBy('first_name', 'asc')->get();

        //get online status
        foreach ($users as $user) {
            $list[$user->id] = $user->is_online;
        }

        return $list;
    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        //common settings
        $page = [
            'page' => 'messages',
            'mainmenu_messages' => 'active',
        ];

        //return
        return $page;
    }
}