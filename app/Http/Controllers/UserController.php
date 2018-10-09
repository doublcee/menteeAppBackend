<?php

namespace App\Http\Controllers;

use App\Mail\New_Member;
use App\Mail\Suspended;
use App\MentorRequest;
use App\Report;
use App\User;
use App\Mail\Notify;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
class UserController extends Controller
{

    public function user(Request $request)
    {
        return $request->user();
    }

    public function signup(Request $request)
    {
        $username = User::where('email', $request['email'])->first();
        if ($username) {
            return response()->json(['emailExist' => 'email taken! choose another'], 200);
        } else {
            $details = [
                'title' => $request['title'],
                'fname' => $request['fname'],
                'lname' => $request['lname'],
                'username' => "N/A",
                'password' => bcrypt($request['password']),
                'cpassword' => $request['password'],
                'gender' => $request['gender'],
                'idtype' => "N/A",
                'email' => $request['email'],
                'careerfield' => $request['careerfield'],
                'phone' => $request['phone'],
                'active' => 0,
                'ban' => 0,
                'user_type' => $request['type'],
                'photo' => 'N/A',
                'photo_path' => 'N/A'
            ];
            event(new Registered($user = User::create($details)));
            if ($user) {
                $title = 'Registration Successful!';
                $title1 = 'New Member';
                $fname = $request['fname'];
                $lname = $request['lname'];
                $email = $request['email'];
                $pass = $request['password'];
                Mail::to($request['email'])->send(new \App\Mail\Registered($title,$fname,$lname,$email,$pass));
                Mail::to('popakindeju@gmail.com')->send(new New_Member($title1, $fname, $lname, $request['user_type'], $request['careerfield']));
                return response()->json(['success' => 'successfully registered! <br/> A mail has been sent to you.']);
            }
        }
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request['email'])->first();
        $password = "" . $request['password'] . "";
        if ($user && Hash::check($password, $user->password)) {
            if ($user->bann == 1) {
                return response()->json(['bann' => 'Hello ' . $user->fname . ', your account has been suspended due to some reasons! Please contact admin at superadmin@pop.com for more.']);
            }elseif ($user->active == 0) {
                return response()->json(['notactive' => 'Hello ' . $user->fname . ', your account is yet to be approved by Admin. Thank you.']);
            }else{
                return $user;
            }

        } else {
            return response()->json(['access' => 'Oops! incorrect credentials, please try again.']);
        }

    }

    public function getMentor()
    {
        $mentor = User::where('user_type', 1)->get();
        if (!$mentor) {
            return response()->json(['noMentor' => 'no mentor at the moment'], 200);
        } else {
            return $mentor;
        }
    }

    public function requestMentor($mentorID, $menteeID)
    {
        $mentReq = new MentorRequest();
        $mentReq->mentor = $mentorID;
        $mentReq->mentee = $menteeID;
        $mentReq->status = '0';
        $saved = $mentReq->save();

        $mentor = User::where('id', $mentorID)->first();
        $title = "Mentee Request";
        $fname = $mentor->fname;
        $lname = $mentor->lname;
        Mail::to($mentor->email)->send(new \App\Mail\Request($title,$fname,$lname));
        if ($saved) {
            return response()->json(['success' => 'request sent successfully'], 200);
        } else {
            return response()->json(['fail' => 'unable to request user'], 200);
        }
    }

    public function pickMentor(Request $request)
    {
        $mentors = User::select('id')
            ->where('user_type', 1)
            ->where('careerfield', $request['careerfield'])
            ->inRandomOrder()->get();
        $count = count($mentors);
        if ($count > 0) {
            if ($count == 1) {
                $selectedmentor = User::select('id')->where('user_type', 1)->first();
                return $this->requestMentor($selectedmentor->id, $request['menteeID']);
            } else if ($count % 2 == 1) {
                $set = 0;
                $middle = intval($count / 2);
                foreach ($mentors as $mentor) {
                    if ($set == $middle) {
                        return $this->requestMentor($mentor->id, $request['menteeID']);
                    }
                    $set++;
                }
            } else {
                $set = 1;
                foreach ($mentors as $mentor) {
                    if ($set == 2) {
                        return $this->requestMentor($mentor->id, $request['menteeID']);
                    }
                    $set++;
                }
            }
        } else {
            return response()->json(['noMentor' => 'No mentor available for your field'], 200);
        }
    }

    public function checkMentor(Request $request)
    {
        $check = MentorRequest::where('mentee', $request['menteeID'])->first();
        if (!$check) {
            return response()->json(['reqStatus' => 1], 200);
        } else if ($check && $check->status == 0) {
            return response()->json(['reqStatus' => 2], 200);
        } else if ($check && $check->status == 1) {
            return response()->json(['reqStatus' => 3], 200);
        }

    }

    public function myMentor(Request $request)
    {
        $getmentor = MentorRequest::where('mentee', $request['menteeID'])->where('status', '1')->first();
        if ($getmentor) {
            return $getmentor;
        }
    }

    public function getmyMentor(Request $request)
    {
        $mymentor = User::where('id', $request['mentorID'])->first();
        if ($mymentor) {
            return $mymentor;
        }
    }

    public function getMentorID(Request $request)
    {
        $getID = MentorRequest::select('mentor')->where('mentee', $request['userID'])->first();
        if ($getID) {
            return $getID;
        }
    }

    public function updateUser(Request $request)
    {
        //  return response()->json(['id:' =>$request['id']], 200);
        $mydata = $request['user'];

        if ($mydata['userType'] == 1) {
            $data = [
                'title' => $mydata['title'],
                'fname' => $mydata['fname'],
                'lname' => $mydata['lname'],
                'password' => bcrypt($mydata['password']),
                'cpassword' => $mydata['password'],
                'gender' => $mydata['gender'],
                'email' => $mydata['email'],
                'phone' => $mydata['phone']
            ];
            $updated = User::where('id', $request['id'])->update($data);
            if ($updated) {
                return response()->json(['success' => 'Profile update Successfully'], 200);
            } else {
                return response()->json(['fail' => 'Error occurred updating profile'], 200);
            }
        } else {
            $data = [
                'fname' => $mydata['fname'],
                'lname' => $mydata['lname'],
                'password' => bcrypt($mydata['password']),
                'cpassword' => $mydata['password'],
                'gender' => $mydata['gender'],
                'email' => $mydata['email'],
                'phone' => $mydata['phone']
            ];
            $updated = User::where('id', $request['id'])->update($data);
            if ($updated) {
                return response()->json(['success' => 'Profile update Successfully'], 200);
            } else {
                return response()->json(['fail' => 'Error occurred updating profile'], 200);
            }
        }
    }

    public function uploadPhoto(Request $request)
    {
        $checkphoto = User::where('id', $request['userID'])->first();
        if ($checkphoto) {
            if ($checkphoto->photo === 'N/A') {
                $image = $request->file('file');
                $input['imagename'] = $request['fileName'];
                $destinationPath = public_path('/uploads');
                $uploaded = $image->move($destinationPath, $input['imagename']);
                if ($uploaded) {
                    $userImage = ['photo' => $input['imagename']];
                    $saved = User::where('id', $request['userID'])->update($userImage);
                    if ($saved) {
                        return 'success';
                    }
                } else {
                    echo 'failed';
                }
            } else {

                $currentImage = public_path("/uploads/" . $checkphoto->photo);
                $delete = unlink($currentImage);
                $deleted = User::where('id', $request['userID'])->update(["photo" => "N/A"]);
                if ($deleted) {
                    // URL::to();
                    $image = $request->file('file');
                    $input['imagename'] = $request['fileName'];
                    $destinationPath = public_path('/uploads');
                    //$destinationPath = URL::to('uploads');
                    $uploaded = $image->move($destinationPath, $input['imagename']);
                    if ($uploaded) {
                        $userImage = ['photo' => $input['imagename']];
                        $saved = User::where('id', $request['userID'])->update($userImage);
                        if ($saved) {
                            echo 'success';
                        }
                    } else {
                        $userImage = ['photo' => "N/A"];
                        $update = User::where('id', $request['userID'])->update($userImage);
                        if ($update) {
                            echo 'failed';
                        }
                    }
                }
            }
        } else {
            echo 'something went wrong';
        }

    }

    public function getPassword(Request $request)
    {
        $title = 'Password Retrieval';
        $check = User::where('email', $request['email'])->first();
        if ($check && $check->email == $request['email']) {
            $msgbody = 'Hello ' . $check->fname . ' your password is ' . $check->cpassword . '.';
            Mail::to($check->email)->send(new Notify($title, $msgbody));
            return response()->json(['Exist' => 'your password successfully sent to your email'], 200);
        } else {
            return response()->json(['notExist' => 'No account with such email'], 200);
        }
    }

    public function allMentors()
    {
        $mentors = User::where('user_type', 1)->where('active',1)->get();
        if ($mentors) {
            return $mentors;
        }
    }

    public function allMentees()
    {
        $mentees = User::where('user_type', 2)->where('active',1)->get();
        if ($mentees) {
            return $mentees;
        }
    }

    public function unappMentors()
    {
        $mentors = User::where('user_type', 1)->where('active', 0)->get();
        return $mentors;
    }

    public function unappMentees()
    {
        $mentors = User::where('user_type', 2)->where('active', 0)->get();
        return $mentors;
    }

    public function approveAccount(Request $request)
    {
        $user = User::where('id',$request['user'])->first();
        $approved = User::where('id', $request['user'])->update(['active' => 1]);
        if ($approved) {
            $title = "Account Successfully Approved";
            $fname = $user->fname;
            $lname = $user->lname;
            $email = $user->email;
            $pass = $user->cpassword;
            Mail::to($user->email)->send(new \App\Mail\AccountApproved($title,$fname,$lname,$email,$pass));
            return response()->json(['success' => 'Account successfully approved.'], 200);
        }
    }

    public function deleteAccount(Request $request)
    {
        $user = User::where('id',$request['user'])->first();
        $approved = User::where('id', $request['user'])->delete();
        if ($approved) {
            $title = "Account Deleted!";
            $fname = $user->fname;
            $lname = $user->lname;
            Mail::to($user->email)->send(new \App\Mail\AccountDeleted($title,$fname,$lname));
            return response()->json(['success' => 'Account successfully deleted.'], 200);
        }
    }

    public function suspendAccount(Request $request)
    {
        $user = User::where('id',$request['user'])->first();
        $approved = User::where('id', $request['user'])->update(['bann' => 1]);
        if ($approved){
            $title = "Account Suspended";
            $fname = $user->fname;
            $lname = $user->lname;
              Mail::to($user->email)->send( new \App\Mail\Suspended($title,$fname,$lname));
            return response()->json(['success' => 'Account successfully suspended.'], 200);
        }
    }

    public function unsuspendAccount(Request $request)
    {
        $user = User::where('id',$request['user'])->first();
        $approved = User::where('id', $request['user'])->update(['bann' => 0]);
        if ($approved) {
            $title = "Account Successfully Unsuspended";
            $fname = $user->fname;
            $lname = $user->lname;
            Mail::to($user->email)->send( new \App\Mail\Unsuspended($title,$fname,$lname));
            return response()->json(['success' => 'Account unsuspended successful.'], 200);
        }
    }

    public function reportUser(Request $request){

        $newrep = new Report();
        $newrep->repBy = $request['by'];
        $newrep->repTo = $request['to'];
        $newrep->reason = $request['reason'];
        $newrep->repBody = $request['body'];
        $newrep->repDate = date('Y-m-d');
        $save = $newrep->save();
        if($save){
            $title = "New Report";
            Mail::to('popakindeju@gmail.com')->send(new \App\Mail\Report($title));
            return response(['success' => 'Your report was successfully sent.<br/> Thank you for reporting.']);
        }
    }

    public function getReports(){
        $reports = Report::with('user')->orderBy('created_at','DESC')->get();
        return $reports;
    }

    public function reportCounts(){
        $reports = Report::with('user')->where('repStatus',0)->orderBy('created_at','DESC')->get();
        return $reports;
    }

    public function reportInfo(Request $request){
        $id = $request['id'];
        Report::where('repID', $id)->update(['repStatus' => 1]);
        $getreportee = Report::where('repID', $id)->first();
        $reportee = User::where('id', $getreportee->repTo)->first();
        $repinfo = Report::where('repID',$id)->first();
        return response()->json(['repinfo'=>$repinfo, 'reportee' => $reportee]);
    }

    public function deleteReport(Request $request){
        $id = $request['id'];
        $delete = Report::where('repID', $id)->delete();
        if($delete){
             return response()->json(['success' => 'Report successfully deleted!'],200);
         }
    }

}