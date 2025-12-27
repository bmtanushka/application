<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for projects
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\PushToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Log;

class PushTokenRepository {

    /**
     * The push token repository instance.
     */
    protected $pushTokens;

    /**
     * Inject dependecies
     */
    public function __construct(PushToken $pushTokens) {
        $this->pushTokens = $pushTokens;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @param array $data optional data payload
     * @return object project collection
     */
    public function search($id = '', $data = []) {

        $pushTokens = $this->pushTokens->newQuery();

        //join: users reminders - do not do this for cronjobs
        if (auth()->check()) {
            $pushTokens->where('user_id', '=', auth()->id());
        }

        return $pushTokens->limit(1)->get();
    }

    /**
     * Create a new record
     * @return mixed int|bool project model object or false
     */
    public function create() {

        //save new user
        $pushToken = new $this->pushTokens;

        //data
        $pushToken->push_token = request('push_token');
        $pushToken->user_id = auth()->id();
        
        //save and return id
        if ($pushToken->save()) {
            return $pushToken->user_id;
        } else {
            Log::error("record could not be created - database error", ['process' => '[PushTokenRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * update a record
     * @param int $id project id
     * @return mixed int|bool  project id or false
     */
    public function update($id) {

        //get the record
        if (!$pushToken = $this->pushTokens->find(auth()->id())) {
            Log::error("record could not be found", ['process' => '[PushTokenRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $id ?? '']);
            return false;
        }

        //general
        $pushToken->push_token = $id;

        //save
        if ($pushToken->save()) {
            return $pushToken->user_id;
        } else {
            return false;
        }
    }

}