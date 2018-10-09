<?php

/*
 * Access Controls
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE");
header("Access-Control-Allow-Headers: Accept, Key, X-Requested-With, Authorization, Content-Type, x-xsrf-token");

/*
 * Api Routes
 */

/* unsecured */
Route::post('/signup', 'UserController@signup');
Route::post('/checkAccess', 'UserController@checkAccess');
Route::post('/login', 'UserController@login');
Route::post('/getPassword','UserController@getPassword');
Route::post('/uploadImage', 'UserController@uploadPhoto');

/*secured grouped routes*/
Route::group(['middleware' => ['auth:api']], function(){
    Route::get('/user', 'UserController@user');
    Route::get('/getMentor', 'UserController@getMentor');
    Route::post('/requestMentor','UserController@requestMentor');
    Route::post('/checkMentor','UserController@checkMentor');
    Route::post('/myMentor', 'UserController@myMentor');
    Route::post('/getmyMentor', 'UserController@getmyMentor');
    Route::post('/getRequest', 'RequestController@getRequests');
    Route::post('/acceptRequest', 'RequestController@acceptRequest');
    Route::post('/declineRequest', 'RequestController@declineRequest');
    Route::post('/myMentees', 'RequestController@myMentees');
    Route::post('/sendMessage','MessageController@sendMessage');
    Route::post('/sentMessages','MessageController@sentMessages');
    Route::post('/inboxMessages','MessageController@inboxMessages');
    Route::post('/checkUnreadMsg','MessageController@checkUnreadMsg');
    Route::post('/replyMsg','MessageController@replyMessage');
    Route::post('/getReplies','MessageController@getReplies');
    Route::post('/updateProfile','UserController@updateUser');
    Route::post('/readMsg', 'MessageController@readMsg');
    Route::post('/deleteMsg', 'MessageController@deleteMsg');
    Route::post('/addGoal','GoalController@addGoal');
    Route::post('/myGoal','GoalController@myGoal');
    Route::post('/mySchedules','GoalController@mySchedules');
    Route::post('pickMentor','UserController@pickMentor');
    Route::post('/processGoal','GoalController@processGoal');
    Route::get('/goalRequests','GoalController@goalRequests');
    Route::post('/processGoalRequest','GoalController@processGoalRequest');
    Route::post('/getAchievements','GoalController@getAchievements');
    Route::post('/getComMessages','CommunityController@getMessages');
    Route::post('/getMentorID','UserController@getMentorID');
    Route::post('/addComMessage','CommunityController@addMessage');
    Route::get('/getMentors','UserController@allMentors');
    Route::get('/getMentees','UserController@allMentees');
    Route::post('/getCoMentees', 'RequestController@getCoMentees');
    Route::get('/unappMentors','UserController@unappMentors');
    Route::get('/unappMentees','UserController@unappMentees');
    Route::post('/approveAccount', 'UserController@approveAccount');
    Route::post('/deleteAccount', 'UserController@deleteAccount');
    Route::post('/suspendAccount', 'UserController@suspendAccount');
    Route::post('/unsuspendAccount', 'UserController@unsuspendAccount');
    Route::post('/reportUser', 'UserController@reportUser');
    Route::get('/getReports', 'UserController@getReports');
    Route::get('/reportCounts', 'UserController@reportCounts');
    Route::post('/reportInfo', 'UserController@reportInfo');
    Route::post('/deleteReport', 'UserController@deleteReport');
});
