<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for projects
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Log;

class ReactionRepository {

    /**
     * The push token repository instance.
     */
    protected $reaction;

    /**
     * Inject dependecies
     */
    public function __construct(Reaction $reaction) {
        $this->reaction = $reaction;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @param array $data optional data payload
     * @return object project collection
     */
    public function search($id = '', $data = []) {

        $reaction = $this->reaction->newQuery();

        if (request()->filled('reaction_resource_type') && request()->filled('reaction_resource_id')) {
            $reaction->where('user_id', '=', auth()->id());
            $notes->where('reaction_resource_type', request('reaction_resource_type'));
            $notes->where('reaction_resource_id', request('reaction_resource_id'));
        }

        return $reaction->limit(1)->get();
    }

    /**
     * Create a new record
     * @return mixed int|bool project model object or false
     */
    public function create() {

        //save new user
        $reaction = new $this->reaction;

        //data
        $reaction->reaction = request('reaction');
        $reaction->reaction_resource_type = request('reaction_resource_type');
        $reaction->reaction_resource_id = request('reaction_resource_id');
        $reaction->user_id = auth()->id();
        
        //save and return id
        if ($reaction->save()) {
            return $reaction->reaction_id;
        } else {
            Log::error("record could not be created - database error", ['process' => '[ReactionRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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
        if (!$reaction = $this->reaction->find(auth()->id())) {
            Log::error("record could not be found", ['process' => '[ReactionRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $id ?? '']);
            return false;
        }

        //general
        $reaction->reaction = $id;

        //save
        if ($reaction->save()) {
            return $reaction->user_id;
        } else {
            return false;
        }
    }

}