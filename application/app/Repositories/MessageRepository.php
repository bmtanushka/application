<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @message    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Message;
use DB;

class MessageRepository {

    /**
     * The leads repository instance.
     */
    protected $message;

    /**
     * Inject dependecies
     */
    public function __construct(Message $message) {
        $this->message = $message;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object messages collection
     */
    public function search($id = '', $data = []) {

        $messages = $this->message->newQuery();

        //joins
        $messages->leftJoin('users', 'users.id', '=', 'messages.message_creatorid');
        
        $messages->leftJoin('messages as p', 'messages.message_parent_id', '=', 'p.message_id');
        
        $messages->leftJoin('reactions', function($join) {
            $join->on('reactions.reaction_resource_id', '=', 'messages.message_unique_id')
             ->where('reaction_resource_type', '=', 'message');
        });
        
        
        // all client fields
        $messages->selectRaw('*');
        
        $messages->select( 'messages.*', 'users.*', 'p.message_text as pmsgtxt', 'p.message_unique_id as pmsgtxtui',
            DB::raw('GROUP_CONCAT(CONCAT(reactions.reaction, ",", reactions.name) SEPARATOR ";") as emoji_data'));
        $messages->groupBy('messages.message_id');
        //default where
        $messages->whereRaw("1 = 1");

        //filter
        if (isset($data['apply_filters']) && $data['apply_filters']) {

            //message_source
            if (isset($data['message_source']) && isset($data['message_target'])) {
                //do not do for team messages
                if ($data['message_target'] == 'team') {
                    $messages->where('messages.message_target', 'team');
                }elseif ($data['message_target'] == 'team2') {
                    $messages->where('messages.message_target', 'team2');
                }elseif ($data['message_target'] == 'team3') {
                    $messages->where('messages.message_target', 'team3');
                }else{
                    $messages->whereIN('messages.message_source', [$data['message_source'],$data['message_target']]);
                    $messages->whereIN('messages.message_target', [$data['message_source'],$data['message_target']]);
                }
            }

            //message_timestamp
            if (isset($data['timestamp'])) {
                $messages->where('messages.message_timestamp', '>', $data['timestamp']);
            }

        }

        //sorting
        $messages->orderBy('messages.message_id', 'desc');

        // Get the results and return them.
        return $messages->paginate(config('system.settings_system_pagination_limits'));
    }
}