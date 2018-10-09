<?php

namespace App\Http\Controllers;

use App\MessageReplies;
use App\Messages;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request){
        $message = new Messages();
        $message->msg_body = $request['msg_body'];
        $message->msg_from = $request['from'];
        $message->msg_to = $request['to'];
        $saved = $message->save();
        if($saved){
            return response()->json(['success' => 'Message Sent'], 200);
        }else{
            return response()->json(['fail' => 'error occured sending message'], 200);
        }

    }

    public function sentMessages(Request $request)
    {
        $getmessages = Messages::with('sentmsgs')
                            ->where('msg_from',$request['senderID'])
                                ->where('del_frm', 0)
                                ->orderBy('created_at','desc')->get();
        if($getmessages){
                return $getmessages;
        }else{
            return response()->json(['noMessages' => 'No messages available'], 200);
        }
    }

    public function inboxMessages(Request $request)
    {
        $getmessages = Messages::with('inboxmsgs')
                                 ->where('msg_to',$request['senderID'])
                                  ->where('del_to', 0)
                                   ->orderBy('created_at','desc')->get();
        if($getmessages){
            return $getmessages;
        }else{
            return response()->json(['noMessages' => 'No messages available'], 200);
        }
    }

    public function checkUnreadMsg(Request $request)
    {
        $getmessages = Messages::with('inboxmsgs')
            ->where('msg_to',$request['userID'])
            ->where('del_to', 0)
            ->where('msg_status', 0)
            ->orderBy('created_at','desc')->get();
        if($getmessages){
            return $getmessages;
        }else{
            return response()->json(['noMessages' => 'No messages available'], 200);
        }
    }

    public function getReplies(Request $request)
    {
        $getreplies = MessageReplies::with('user')->where('repMSG',$request['msgID'])->get();
        if($getreplies){
            return $getreplies;
        }else{
            return response()->json(['noReplies' => 'No replies available'], 200);
        }
    }

    public function replyMessage(Request $request)
    {
        $reply = new MessageReplies();
        $reply->reply_text = $request['reply'];
        $reply->repMSG = $request['msg_id'];
        $reply->user_id = $request['user_id'];
        $saved = $reply->save();

        if($saved){
            return response()->json(['success' => 'reply sent'], 200);
        }else{
            return response()->json(['fail' => 'error occured replying'], 200);
        }
    }

    public function readMsg(Request $request)
    {
        $checkRead = Messages::where('msg_id', $request['msgID'])->first();
        if($checkRead->msg_status == 1){
            return response()->json(['msgRead' => 'message already read'],200);
        }else{
            $msgID = ['msg_status' => 1];
            $readMSG = Messages::where('msg_id', $request['msgID'])->update($msgID);
            if($readMSG){
                return response()->json(['success' => 'message successfully read'],200);
            }
        }

    }

    public function deleteMsg(Request $request)
    {
        if($request['type'] == 1){
                $deleted = Messages::where('msg_id', $request['msgID'])->update(['del_to' => 1]);
                if($deleted){
                    return response()->json(['success' => 'message deleted'],200);
                }else{
                    return response()->json(['fail' => 'unable to delete message'],200);
            }
        }else{
                $deleted = Messages::where('msg_id', $request['msgID'])->update(['del_frm' => 1]);
                if($deleted){
                    return response()->json(['success' => 'message deleted'],200);
                }else{
                    return response()->json(['fail' => 'unable to delete message'],200);
                }
        }
    }
}
