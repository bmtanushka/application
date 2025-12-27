<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for home page
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PushToken; // Assuming you have a Token model

class PushTokenController extends Controller
{
    public function saveToken(Request $request)
    {
        // Validate request data if needed

        $tokenValue = $request->input('push_token');

        
        // Check if token already exists in the database
        $pushToken = PushToken::where('user_id', auth()->id())->first();

        if ($pushToken) {
            // Token exists, update its entry
            $pushToken->update([
                'push_token' => $tokenValue // Optionally, update other fields if needed
            ]);
        } else {
            // Token does not exist, create a new entry
            PushToken::create([
                'push_token' => $tokenValue,
                'user_id'=> auth()->id()
            ]);
        }

        return response()->json(['message' => 'Token saved successfully']);
    }
}
