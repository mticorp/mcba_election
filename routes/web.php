<?php

// Route::get('.well-known/apple-app-site-association', function () {
//     return response()->download("upload/apple-app-site-association");
// });

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/',function(){
    return redirect('/login');
});

Route::get('/admin/redirect','HomeController@index')->name('admin');

// Authentication Routes...
Route::get('admin/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('admin/login', 'Auth\LoginController@login');

// Password Reset Routes...
Route::get('admin/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('admin/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('admin/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('admin/password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/', 'Voter\LoginController@index')->name('voter.index');
Route::get('/vote/result/{election_id}', 'Voter\VoteController@result')->name('vote.result-page');
Route::get('/vote/{election_id}','Voter\LoginController@VoteIndex')->name('vote.voter.index');
Route::post('/vote/redirect/','Voter\LoginController@VoteLogin')->name('vote.voter.login');

// Route::get('/vote/register/check-Form','Voter\MemberController@register')->name('vote.member.register');
// Route::post('/vote/check_refer_code/','Voter\MemberController@check')->name('vote.member.register.check');

// Route::get('/vote/register/refill-form/{member_id}','Voter\MemberController@refill')->name('vote.member.register.form');

// Route::post('/vote/register/confirm', 'Voter\MemberController@confirm')->name('vote.member.register.confirm');
// Route::get('/vote/register/complete/','Voter\MemberController@completeMessage')->name('vote.member.register.complete');

//start login routes...
Route::get('app/{voter_id}','Voter\LoginController@Link')->name('vote.link');
Route::get('already','Voter\VoteController@already')->name('vote.already');
Route::get('unauthorized','Voter\VoteController@unauthorized')->name('vote.unauthorized');

Route::middleware(['voter'])->group(function () {
    Route::post('/sendOtp', 'Voter\LoginController@sendOtp')->name('vote.sendOtp');
    Route::get('/verify', 'Voter\LoginController@verifyView')->name('voter.verifyView');
    Route::post('/verifyOtp', 'Voter\LoginController@verifyOtp')->name('voter.verifyOtp');

    Route::get('/select-Election','Voter\LoginController@selectElection')->name('voter.select.election');
    //candidates page routes...
    Route::get('vote/candidate/{election_id}', 'Voter\VoteController@candidateList')->name('vote.candidatelist');
    Route::get('vote/canddidate-details/{election_id}/{id}', 'Voter\VoteController@CandidateDetail')->name('vote.candidate.detail');

    //faq routes...
    Route::get('vote/FAQ/{election_id}','Voter\VoteController@faq')->name('vote.faq');
    Route::post('vote/FAQ-store','Voter\VoteController@faqStore')->name('vote.faq.store');

    //complete routes...
    Route::get('vote/complete/{election_id}', 'Voter\VoteController@complete')->name('vote.complete');
    Route::post('vote/complete/store-lucky-code','Voter\VoteController@storeCode')->name('lucky.code.store');

    //trace route...

    Route::post('vote/voter-change-status', 'Voter\VoteController@voterchangeStatus')->name('voter.change.status');
    Route::post('vote/changestatus', 'Voter\VoteController@changeStatus')->name('vote.changestatus');

    //vote store route...
    Route::post('vote/candidate/store', 'Voter\VoteController@store')->name('vote.store');
});

Route::group(['middleware' => 'auth'], function () {
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        //election route
        Route::get('/election', 'Admin\ElectionController@index')->name('admin.election.index');
        Route::get('/election/create','Admin\ElectionController@create')->name('admin.election.create');
        Route::post('/election', 'Admin\ElectionController@store')->name('admin.election.store');
        Route::get('/election/edit/{election_id}', 'Admin\ElectionController@edit')->name('admin.election.edit');
        Route::post('/election/update', 'Admin\ElectionController@update')->name('admin.election.update');
        Route::get('/election/destroy/{election_id}', 'Admin\ElectionController@destroy')->name('admin.election.delete');

        Route::post('/changeStatus', 'Admin\ElectionController@changeStatus')->name('election.changestatus');
        Route::post('/force-changeStatus','Admin\ElectionController@forcechangeStatus')->name('election.force.changestatus');
        Route::get('/changeLucky','Admin\ElectionController@luckyFlag')->name('election.luckyFlag');
        //end

        //Question route
        Route::get('/question/{election_id}', 'Admin\QuestionController@index')->name('admin.question.index');
        Route::post('/question/create', 'Admin\QuestionController@store')->name('admin.question.store');
        Route::get('/question/destroy/{id}', 'Admin\QuestionController@destroy')->name('admin.question.delete');
        Route::get('/question/edit/{question_id}', 'Admin\QuestionController@edit')->name('admin.question.edit');
        Route::post('/question/update', 'Admin\QuestionController@update')->name('admin.question.update');

        //lucky route
        Route::get('/lucky-draw/{election_id}','Admin\LuckyController@index')->name('admin.lucky.index');
        Route::get('/lucky/export/{election_id}','Admin\LuckyController@export')->name('admin.lucky.export');

        //user management route
        Route::get('/user', 'Admin\UserController@index')->name('admin.user.index');
        Route::post('/create-user', 'Admin\UserController@store')->name('admin.user.store');
        Route::get('/user/edit/{id}', 'Admin\UserController@edit')->name('admin.user.edit');
        Route::post('/user/update/', 'Admin\UserController@update')->name('admin.user.update');
        Route::get('/user/destroy/{id}', 'Admin\UserController@destroy')->name('admin.user.delete');

        //company route
        Route::get('/company', 'Admin\CompanyController@index')->name('admin.company.index');
        Route::post('/company/create', 'Admin\CompanyController@store')->name('admin.company.store');
        Route::get('/company/destroy/{id}', 'Admin\CompanyController@destroy')->name('admin.company.delete');
        Route::get('/company/edit/{id}', 'Admin\CompanyController@edit')->name('admin.company.edit');
        Route::post('/company/update', 'Admin\CompanyController@update')->name('admin.company.update');


        //Logo route
        Route::get('/logo', 'Admin\LogoController@index')->name('admin.logo.index');
        Route::post('/logo/create', 'Admin\LogoController@store')->name('admin.logo.store');
        Route::get('/logo/destroy/{id}', 'Admin\LogoController@destroy')->name('admin.logo.delete');
        Route::get('/logo/edit/{id}', 'Admin\LogoController@edit')->name('admin.logo.edit');
        Route::post('/logo/update', 'Admin\LogoController@update')->name('admin.logo.update');


        //Favicon route
        Route::get('/favicon', 'Admin\FaviconController@index')->name('admin.favicon.index');
        Route::post('/favicon/create', 'Admin\FaviconController@store')->name('admin.favicon.store');
        Route::get('/favicon/destroy/{id}', 'Admin\FaviconController@destroy')->name('admin.favicon.delete');
        Route::get('/favicon/edit/{id}', 'Admin\FaviconController@edit')->name('admin.favicon.edit');
        Route::post('/favicon/update', 'Admin\FaviconController@update')->name('admin.favicon.update');

        //SMS route
        Route::get('/sms&reminder', 'Admin\SmsController@index')->name('admin.sms.index');
        
        Route::get('/sms&reminder/sms-create/{id}', 'Admin\SmsController@createpage')->name('admin.smscreatepage.index');
        Route::post('/sms&reminder/sms-update/{id}', 'Admin\SmsController@update')->name('admin.sms.update');

        //Reminder route
        Route::get('/sms&reminder/reminder-create/{id}', 'Admin\SmsController@remindercreatepage')->name('admin.remindercreatepage.index');
        Route::post('/sms&reminder/reminder-update{id}', 'Admin\SmsController@reminderupdate')->name('admin.sms.reminderupdate');        

        Route::get('/sms&reminder/description-create/{id}', 'Admin\SmsController@descriptioncreatepage')->name('admin.description.index');
        Route::post('/sms&reminder/description-update{id}', 'Admin\SmsController@descriptionupdate')->name('admin.sms.descriptionupdate');        


        //dashboard route
        Route::get('/dashboard/{election_id}','Admin\DashboardController@index')->name('admin.dashboard');

        //candidate route
        Route::get('/candidate/index/{election_id}', 'Admin\CandidateController@index')->name('admin.candidate.index');
        Route::get('/candidate/create/{election_id}','Admin\CandidateController@create')->name('admin.candidate.create');
        Route::post('/candidate/create', 'Admin\CandidateController@store')->name('admin.candidate.store');
        Route::get('/candidate/detail/{election_id}/{candidate_id}','Admin\CandidateController@detail')->name('admin.candidate.detail');
        Route::get('/candidate/edit/{election_id}/{candidate_id}', 'Admin\CandidateController@edit')->name('admin.candidate.edit');
        Route::post('/candidate/update', 'Admin\CandidateController@update')->name('admin.candidate.update');
        Route::get('/candidate/destroy/{election_id}/{candidate_id}', 'Admin\CandidateController@destroy')->name('admin.candidate.delete');
        Route::get('/candidate/excel-import/{election_id}','Admin\CandidateController@excelImport')->name('admin.candidate.excel.import');
        Route::post('/candidate/import/','Admin\CandidateController@Import')->name('admin.candidate.excel-import');
        Route::get('/candidate-excel-download','Admin\CandidateController@export')->name('candidate-excel-export');

        //voting information route
        Route::get('/voting/votingrecord/{election_id}', 'Admin\VotingController@votingRecord')->name('admin.election.voting-record');
        Route::get('/voting/rejectvotingrecord/{election_id}', 'Admin\VotingController@rejectVotingRecord')->name('admin.election.rejectvoting-record');
        Route::get('/voting/notvotedrecord/{election_id}', 'Admin\VotingController@notVotedRecord')->name('admin.election.notvoted-record');
        Route::get('/voting/votingresult/{election_id}', 'Admin\VotingController@votingResult')->name('admin.election.voting-result');

        Route::get('/voting/result/candidate-download', 'Admin\VotingController@get_candidateList')->name('admin.candidate-excel.download');
        Route::get('/voting/result/question-download', 'Admin\VotingController@get_questionList')->name('admin.question-excel.download');
        Route::get('/voting/result/get-result', 'Admin\VotingController@get_result')->name('admin.election.get-result');
        Route::get('/voting/result/get-ques-result', 'Admin\VotingController@get_ques_result')->name('admin.election.get-ques-result');
        Route::get('/voting/result/detail/{candidate_id}/{election_id}', 'Admin\VotingController@votingresultshow')->name('admin.election.votingresult.show');
        Route::get('/voting/ques-result/detail/{question_id}/{election_id}', 'Admin\VotingController@questionvotingresultshow')->name('admin.election.question-votingresult.show');
        //answer route
        // Route::get('/voting/answer/{election_id}','Admin\VotingController@answer')->name('admin.answer.index');
        //register route
        Route::get('/register/', 'Admin\MRegisterController@index')->name('admin.register.index');
        Route::get('/register/create','Admin\MRegisterController@create')->name('admin.register.create');
        Route::post('/register/store', 'Admin\MRegisterController@store')->name('admin.register.store');
        Route::get('/register/detail/{member_id}','Admin\MRegisterController@detail')->name('admin.register.detail');
        Route::get('/register/edit/{member_id}', 'Admin\MRegisterController@edit')->name('admin.register.edit');
        Route::post('/register/update', 'Admin\MRegisterController@update')->name('admin.register.update');
        Route::get('/register/destroy/{member_id}', 'Admin\MRegisterController@destroy')->name('admin.register.delete');
        Route::get('/register/excel-import','Admin\MRegisterController@excelImport')->name('admin.register.excel.import');
        Route::post('/register/import/','Admin\MRegisterController@Import')->name('admin.register.excel-import');
        Route::get('/member-excel-download','Admin\MRegisterController@export')->name('member-excel-download');
    });

    Route::prefix('admin')->group(function(){
        Route::get('/index','Generator\GenerateController@index')->name('generator.index');

        Route::get('/vid/list/','Generator\GenerateController@vidList')->name('generator.vid-list');
        Route::get('/vid/generate','Generator\GenerateController@generateVidBlade')->name('generator.generate.vid');
        Route::get('/vid/excel/export','Generator\GenerateController@export')->name('generator.excel.export');

        //reminder
        Route::post('/vid-reminder','Generator\GenerateController@reminder')->name('generator.vid.reminder');
        Route::post('/vid-reminder(Email)','Generator\GenerateController@emailReminderOnly')->name('generator.vid.reminder.emailOnly');
        Route::post('/vid-reminder(SMS)','Generator\GenerateController@smsReminderOnly')->name('generator.vid.reminder.smsOnly');

        Route::post('/generated-vid/store', 'Generator\GenerateController@store')->name('vid.store');

        //message
        Route::post('/send-message', 'Generator\GenerateController@sendMessage')->name('vid.message');
        Route::post('/send-message(SMS)', 'Generator\GenerateController@smsMessageOnly')->name('vid.message.smsOnly');
        Route::post('/send-message(Email)', 'Generator\GenerateController@emailMessageOnly')->name('vid.message.emailOnly');

        Route::post('/register/send-message', 'Admin\MRegisterController@sendMessage')->name('member.message');
        Route::post('/register/send-message(SMS)', 'Admin\MRegisterController@smsMessageOnly')->name('member.message.smsOnly');
        Route::post('/register/send-message(Email)', 'Admin\MRegisterController@emailMessageOnly')->name('member.message.emailOnly');

        Route::post('/excel/generate-vid', 'Generator\GenerateController@excelGenerate')->name('vid.excel.generate-vid');
        Route::get('/excel-download','Generator\GenerateController@excelDownload')->name('vid.excel.template-download');
    });

    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
});


//clear route
Route::get('/clear-cache', function () {
	$exitCode = Artisan::call('config:clear');
	$exitCode = Artisan::call('cache:clear');
	$exitCode = Artisan::call('config:cache');
	return 'DONE'; //Return anything
});