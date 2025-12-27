<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for comments
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Comment;
use DB;
use Illuminate\Http\Request;
use Log;

class CommentRepository {

    /**
     * The comments repository instance.
     */
    protected $comments;

    /**
     * Inject dependecies
     */
    public function __construct(Comment $comments) {
        $this->comments = $comments;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object comment collection
     */
    public function search($id = '') {

        //new query
        $comments = $this->comments->newQuery();

        // all client fields
        $comments->selectRaw('*');
        
        $comments->select( 'comments.*','users.*','clients.*', 'p.comment_text as pcmttxt', 'p.comment_id as pcmtid',
            DB::raw('GROUP_CONCAT(CONCAT(reactions.reaction, ",", reactions.name) SEPARATOR ";") as emoji_data'));
        $comments->groupBy('comments.comment_id');

        //joins
        $comments->leftJoin('users', 'users.id', '=', 'comments.comment_creatorid');
        $comments->leftJoin('clients', 'clients.client_id', '=', 'comments.comment_clientid');
    
        $comments->leftJoin('comments as p', 'comments.commentparent_id', '=', 'p.comment_id');
        
        $comments->leftJoin('reactions', function($join) {
            $join->on('reactions.reaction_resource_id', '=', 'comments.comment_id')
             ->where('reaction_resource_type', '=', 'comment');
        });
        
        //default where
        $comments->whereRaw("1 = 1");

        //limit by id
        if (is_numeric($id)) {
            $comments->where('comments.comment_id', $id);
        }

        //filters: resource type
        if (request()->filled('commentresource_type')) {
            $comments->where('comments.commentresource_type', request('commentresource_type'));
        }

        //filters: resource type
        if (request()->filled('commentresource_id')) {
            $comments->where('comments.commentresource_id', request('commentresource_id'));
        }

        //filter clients
        if (request()->filled('filter_comment_clientid')) {
            $invoices->where('comments.comment_clientid', request('filter_comment_clientid'));
        }

        //default sorting
        $comments->orderBy('comments.comment_created', 'desc');
        //$comments->orderBy('commentsuperparent_id', 'desc')->orderBy('commentparent_id', 'asc')->orderBy('comment_created');
        //sorting
    //$comments->orderByRaw("CASE WHEN commentparent_id IS NULL THEN comment_created ELSE commentparent_id END, comment_created");
    //$sql = $comments->toSql();
    //dd($sql);

        return $comments->paginate(100000);
    }

    /**
     * Create a new record
     * @return mixed int|bool
     */
    public function create() {

        //save new user
        $comment = new $this->comments;
        $reply = false;
        $replyReply = false;
        $edit = false;
        $pComment = false;

        //data
        $comment->comment_creatorid = auth()->id();
        $comment->comment_text = request('comment_text');
        $comment->commentresource_type = request('commentresource_type');
        $comment->commentresource_id = request('commentresource_id');
        $comment->commentparent_id = request('commentparent_id');
        $comment->comment_id = request('commentedit_id');
        
        //check if parent comment available
        
        if(request('commentparent_id')!=null){
            //$pComment = $this->search(request('commentparent_id'));
            //dd($pComment[0]->comment_id);
            //if($pComment[0]->comment_id == $pComment[0]->commentsuperparent_id && $pComment[0]->commentparent_id == $pComment[0]->commentsuperparent_id){
            //    $reply = true;
            //}
            //if($pComment[0]->commentparent_id != $pComment[0]->commentsuperparent_id){
            //    $replyReply = true;
            //}
            $pComment = true;
        }
        
        if(request('commentedit_id')!=null){
            $edit = true;
            $eComment = $this->search(request('commentedit_id'))->first();
            $eComment->comment_text = request('comment_text');
            if ($eComment->save()){
                return $eComment->comment_id;   
            } else {
                Log::error("updating record failed - database error", ['process' => '[CommentRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                return false;
            }
        }

        //save and return id
        if ($comment->save()) {
            //if(!$reply && !$replyReply){
            if(!$pComment){
                $comment->commentparent_id = $comment->comment_id;
                $comment->commentsuperparent_id = $comment->comment_id;
                $comment->save();
            }
            if($reply){
                $comment->commentparent_id = $comment->comment_id;
                $comment->commentsuperparent_id = $pComment[0]->comment_id;
                $comment->save(); 
            }
            if($replyReply){
                $comment->commentparent_id = $pComment[0]->comment_id;
                $comment->commentsuperparent_id = $pComment[0]->commentsuperparent_id;
                $comment->save(); 
            }
            //added for new reply version
            if($pComment){
                $comment->commentparent_id = request('commentparent_id');
                $comment->commentsuperparent_id = request('commentparent_id');
                $comment->save(); 
            }
            return $comment->comment_id;
        } else {
            Log::error("saving record failed - database error", ['process' => '[CommentRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
}