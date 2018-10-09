<?php

namespace App\Http\Controllers;

use App\Achievement;
use App\Goals;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function addGoal(Request $request)
    {
        $newGoal = new Goals();
        $newGoal->goalDesc = $request['goalDesc'];
        $newGoal->gStartDate = $request['startDate'];
        $newGoal->gEndDate = $request['endDate'];
        $newGoal->goalUser = $request['userID'];
        $newGoal->points = 3;
        $saveGoal = $newGoal->save();
        if($saveGoal){
            return response()->json(['success'=>'Goal successfully added'],200);
        }else{
            return response()->json(['fail'=>'error occured adding goal'],201);
        }
    }

    public function myGoal(Request $request){
        $myGoal = Goals::where('goalUser', $request['userID'])->get();
        if($myGoal){
            return $myGoal;
        }else{
            return response()->json(['fail'=>'error occured getting goals']);
        }
    }

    public function mySchedules(Request $request){
        $myGoal = Goals::where('goalUser', $request['userID'])
                                ->where('gStatus',0)->get();
        if($myGoal){
            return $myGoal;
        }else{
            return response()->json(['fail'=>'error occured getting goals']);
        }
    }


    public function processGoal(Request $request){
        if($request['pType'] == 1){
            $dataApproved = ['gStatus' => 1, 'points' => 3, 'dateCompleted' => date('y-m-d')];
            $approved = Goals::where('goal_id', $request['goalID'])->update($dataApproved);
            if($approved){
               return $this->addPointRecord($request['userID']);
            }else{return response()->json(['fail' => 'error occured at completing goal'],200);}
        }else{
            $dataAborted = ['gStatus' => 2];
            $aborted = Goals::where('goal_id', $request['goalID'])->update($dataAborted);
            if($aborted){
                return response()->json(['success' => 'Goal aborted successfully'],200);
            }else{return response()->json(['fail' => 'Goal completed! awaiting approval'],200);}
        }
    }

    public function addPointRecord($userID){
         $checkAch = Achievement::where('achUser',$userID)->first();
         if(!$checkAch){
            $ach = new Achievement();
            $ach->achNum = 0;
            $ach->achPoints = 0;
            $ach->achUser = $userID;
            $save = $ach->save();
            if($save){
                return response()->json(['success' => 'Goal completed! awaiting approval'],200);
            }
         }else{
             return response()->json(['success' => 'Goal completed! awaiting approval'],200);
         }
    }

    public function goalRequests(Request $request)
    {
        $goalRequests = Goals::with('user')->where('gStatus',1)->where('approval',0)->get();
        if($goalRequests){
            return $goalRequests;
        }
    }

    public function processGoalRequest(Request $request){
        if($request['pType'] == 1){
            $appGoal = Goals::where('goal_id',$request['goalID'])->update(['approval' => 1]);
            if($appGoal){
                $getuserAch = Achievement::where('achUser',$request['userID'])->first();
                if($getuserAch){
                    $addAch = Achievement::where('achUser',$request['userID'])->update(['achNum'=>$getuserAch->achNum+1,'achPoints'=>$getuserAch->achPoints+3]);
                    if($addAch){
                        return response()->json(['success' => 'request accepted'],200);
                    }else{
                        return response()->json(['fail' => 'error occurred processing request'],200);
                    }
                }
            }
        }else{
            $appGoal = Goals::where('goal_id',$request['goalID'])->update(['approval' => 2]);
            if($appGoal){
                return response()->json(['success' => 'request declined'],200);
            }else{
                return response()->json(['fail' => 'error occurred processing request'],200);
            }
        }
    }

    public function getAchievements(Request $request){
        $achievements = Achievement::with('user')->where('achNum', '>', 0)->orderBy('achPoints','DESC')->get();
        if($achievements){
            return $achievements;
        }else{
            return response()->json(['fail' => 'Error occurred getting achievements'],200);
        }
    }

}
