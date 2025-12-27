<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for home page
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reaction; // Assuming you have a Token model

class ReactionController extends Controller
{
    public function saveReaction(Request $request)
    {
        // Validate request data if needed
        $reaction = request('reaction');
        $reactionResourceType = request('reaction_resource_type');
        $reaction_resource_id = request('reaction_resource_id');
        
        // Check if token already exists in the database
        $reactionObj = Reaction::where('user_id', auth()->id())->where('reaction_resource_type', $reactionResourceType)->where('reaction_resource_id', $reaction_resource_id)->first();
        //print_r(auth()->user());
        if ($reactionObj) {
            // Token exists, update its entry
            $reactionObj->update([
                'reaction' => $reaction // Optionally, update other fields if needed
            ]);
        } else {
            // Token does not exist, create a new entry
            Reaction::create([
                'reaction' => $reaction,
                'reaction_resource_type' => $reactionResourceType,
                'reaction_resource_id' => $reaction_resource_id,
                'user_id'=> auth()->id(),
                'name'=> auth()->user()->first_name." ".auth()->user()->last_name
            ]);
        }

        return response()->json(['message' => 'Reaction saved successfully']);
    }
}
