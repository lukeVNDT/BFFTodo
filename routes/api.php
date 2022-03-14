<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Upcoming;
use App\Models\TodayTsk;
use App\Models\Project;
use App\Http\Resources\UpcomingResource;
use App\Http\Resources\TodayTskResource;
use App\Http\Resources\ProjectResource;



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// ** Upcoming Task ** //

//get all upcoming task
Route::get('/upcoming', function(){
    $upcoming = Upcoming::all();

    return UpcomingResource::collection($upcoming);
});

//add new task
Route::post('/upcoming', function(Request $req){
    return Upcoming::create([
        'title' => $req->title,
        'taskId' => $req->taskId,
        'project_id' => $req->project_id,
        'waiting' => $req->waiting
    ]);
});

//update upcoming task
Route::post('/editupcomingtask/{id}',function(Request $req, $id){
    return Upcoming::where('id', $id)->update([
        'title' => $req->title
    ]);
});

//delete upcoming task
Route::delete('/upcoming/{taskId}', function($taskId){
    Upcoming::where('taskId', $taskId)->delete();

    return 204;
});


//get Upcoming task by project
Route::get('/upcomingbyid/{project_id}', function($project_id){
    $upcomingById = Upcoming::with('UpcomingById')
    ->where('project_id', $project_id)->get();
    return UpcomingResource::collection($upcomingById);
});

// ** Today task ** //

//get all daily task
Route::get('/dailytask', function(){
    $dailytask = TodayTsk::all();

    return TodayTskResource::collection($dailytask);
});




//get daily task by Id project
Route::get('/dailytaskbyid/{project_id}', function($project_id){
    $dailytaskbyid = TodayTsk::with('taskbyidproject')
    ->where('project_id', $project_id)->get();

    return TodayTskResource::collection($dailytaskbyid);
});


//add new today task
Route::post('/dailytask', function(Request $req){
    return TodayTsk::create([
        'id' => $req->id,
        'title' => $req->title,
        'taskId' => $req->taskId,
        'project_id' => $req->project_id
    ]);
});

//delete today task
Route::delete('/dailytask/{taskId}', function($taskId){
    TodayTsk::where('taskId', $taskId)->delete();

    return 204;
});

//update daily task name
Route::post('/updatedailytask/{taskId}', function(Request $req, $taskId){
    return TodayTsk::where('taskId', $taskId)->update([
        'title' => $req->title
    ]);
});

// ** Project ** //

//get all project
Route::get('/project', function(){
    $project = Project::all();

    return ProjectResource::collection($project);
});

//add new Project
Route::post('/insertproject', function(Request $req){
    $project = Project::create([
        'title' => $req->title,
    ]);
    
    return response()->json(["data" => $project], 201);
});

//delete project
Route::delete('/project/{project_id}', function($project_id){
    Project::where('id', $project_id)->delete();

    return 204;
});

//search project
Route::get('/project/{keyword}', function($keyword){
    $result = Project::where('title','LIKE','%'.$keyword.'%')->get();

    return ProjectResource::collection($result);
});

//update project name
Route::put('/updateproject/{project_id}', function($project_id, Request $req){
    Project::where('id', $project_id)->update([
        "title" => $req->title
    ]);

    return 201;
});