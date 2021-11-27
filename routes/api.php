<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::post('userlogin', 'Api\MobileApiController@userlogin');


// $router->group(['middleware' => 'auth:api'], function () use ($router) {
//     Route::post('userlogout', 'Api\MobileApiController@userlogout');
// });

// Route::middleware('accesstoken')->group(function () {

//     Route::post('voter/login', 'Api\MobileApiController@Login'); // Voter Login APIp
//     Route::post('voter/send-OTP', 'Api\MobileApiController@sendOTP');

//     Route::post('voter/verfiy', 'Api\MobileApiController@verify');
//     Route::get('voter/election-list/{voter_id}', 'Api\MobileApiController@electionlist');

//     Route::get('voter/get-candidate-list/{election_id}', 'Api\MobileApiController@candidatelist');
//     Route::post('voter/vote/store', 'Api\MobileApiController@VoteStore');

//     Route::get('voter/question-list/{election_id}/{voter_id}', 'Api\MobileApiController@questionlist');

//     Route::post('voter/answer/store', 'Api\MobileApiController@storeanswerandquestion');

//     Route::get('voter/voter-candidate-list/{election_id}/{voter_id}', 'Api\MobileApiController@VoterCandidateList');
//     Route::get('voter/lucky-code/{election_id}/{voter_id}', 'Api\MobileApiController@GenerateluckyCode');

//     Route::get('votingresult/{election_id}/', 'Api\MobileApiController@votingresult');

//     //in App get Election_id By Clicking the Election List .Carry the Voter_id and Elcetion_id
//     //Route::post('membercheckform', 'Api\MobileApiController@membercheckform');
//     //Route::post('confirmembercheckform', 'Api\MobileApiController@confirmembercheckform');
// });
