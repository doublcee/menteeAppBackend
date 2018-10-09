<?php

namespace App\Http\Controllers;

use App\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function getMessages(Request $request){
       $getMessage = Community::with('user')->where('comID', $request['comID'])->orderBy('created_at','ASC')->get();
       if($getMessage){
            return $getMessage;
       }else{
           return response()->json(['fail' => 'error occured getting Messages'],200);
       }
    }

    public function addMessage(Request $request){
        $newMsg = new Community();
        $newMsg->comMsgBody = $request['msgBody'];
        $newMsg->comMsgBy = $request['userID'];
        $newMsg->comID = $request['comID'];
        $newMsg->comMsgDate = date('Y-m-d');
        $newMsg->comMsgTime = date('h:i:s');
        $saved = $newMsg->save();
        if($saved){
            return response()->json(['success' => 'message delivered'],200);
        }else{
            return response()->json(['fail' => 'error occured posting'],200);
        }
    }
}
