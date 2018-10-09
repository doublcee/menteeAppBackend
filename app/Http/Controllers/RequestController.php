<?php

namespace App\Http\Controllers;


use App\MentorRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function getRequests(Request $request)
    {
        $requests = MentorRequest::with('user')
                    ->where('mentor', $request['mentorID'])
                        ->where('status','0')->where('friends', '0')
                            ->get();
        if($requests){
            return $requests;
        }else{
            return response()->json(['noRecord' => 'No requests at the moment!'], 200);
        }
    }

    public function myMentees(Request $request)
    {
        $mentees = MentorRequest::with('user')
                    ->where('mentor', $request['mentorID'])
                    ->where('status','1')->where('friends', '1')
                    ->get();
        if($mentees){
            return $mentees;
        }
    }

    public function getCoMentees(Request $request){
        $comentees = MentorRequest::with('user')
            ->where('mentor', $request['mentorID'])
            ->where('mentee', '!=', $request['menteeID'])
            ->where('status','1')->where('friends', '1')
            ->get();
        if($comentees){
            return $comentees;
        }
    }

    public function acceptRequest(Request $request){
        $data = ['status' => '1', 'friends' => '1'];
        $update = MentorRequest::where('req_id', $request['requestID'])->update($data);
        if($update){
            return response()->json(['success' => 'Request Accepted'], 200);
        }else{
            return response()->json(['failed' => 'Error occured accepting this request'], 200);
        }
    }

    public function declineRequest(Request $request)
    {
        $delete = MentorRequest::where('req_id', $request['requestID'])->delete();
        if($delete){
            return response()->json(['success' => 'Request Decline successful'], 200);
        }else{
            return response()->json(['failed' => 'Error occured deleting this request'], 200);
        }
    }
}
