<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Question extends Model
{
    //
    protected $fillable = [
        'ques', 'no', 'election_id', 'image'
    ];

    public function QuesSummary($election_id, $question_id = null)
    {
        if ($question_id != 'null') {
            $result = DB::table('questions')
                ->leftjoin('answers', 'questions.id', '=', 'answers.ques_id')
                ->where('questions.election_id', $election_id)
                ->select('questions.id as question_id', 'questions.ques as question_title', 'questions.des', 'questions.image', DB::raw("COUNT(CASE answers.ans_flag WHEN '1' THEN 1 ELSE NULL END) AS yes_ans"), DB::raw("COUNT(CASE answers.ans_flag WHEN '0' THEN 1 ELSE NULL END) AS no_ans"))
                ->groupBy('questions.id', 'questions.ques', 'questions.des', 'questions.image')
                ->orderBy('questions.id', 'asc')
                ->get();
            foreach ($result as $key => $value) {
                $value->summery = DB::select("SELECT * FROM (SELECT COUNT(*) as yes_count FROM answers as ans WHERE ans.ans_flag = 1 AND ans.ques_id = $value->question_id) AS yesAns, (SELECT COUNT(*) as no_count FROM answers as noans WHERE noans.ans_flag = 0 AND noans.ques_id = $value->question_id) AS noAns LIMIT 1")[0];
            }
        } else {
            $result = DB::table('questions')
                ->leftjoin('answers', 'questions.id', '=', 'answers.ques_id')
                ->where('questions.election_id', $election_id)
                ->where('questions.id', $question_id)
                ->select('questions.id as question_id', 'questions.ques as question_title', 'questions.des', 'questions.image', DB::raw("COUNT(CASE answers.ans_flag WHEN '1' THEN 1 ELSE NULL END) AS yes_ans"), DB::raw("COUNT(CASE answers.ans_flag WHEN '0' THEN 1 ELSE NULL END) AS no_ans"))
                ->groupBy('questions.id', 'questions.ques', 'questions.des', 'questions.image')
                ->orderBy('questions.id', 'asc')
                ->first();
            $result->summery = DB::select("SELECT * FROM (SELECT COUNT(*) as yes_count FROM answers as ans WHERE ans.ans_flag = 1 AND ans.ques_id = $value->question_id) AS yesAns, (SELECT COUNT(*) as no_count FROM answers as noans WHERE noans.ans_flag = 0 AND noans.ques_id = $value->question_id) AS noAns LIMIT 1")[0];
        }
        return $result;
    }

    public function yes_count()
    {
        return $this->hasMany('App\Answer', 'ques_id')->where('ans_flag', 1);
    }

    public function no_count()
    {
        return $this->hasMany('App\Answer', 'ques_id')->where('ans_flag', 0);
    }

    public function answers()
    {
        return $this->hasMany('App\Answer', 'ques_id')
            ->select('answers.*', 'voter.name as voter_name', 'election_voters.done as voter_done', 'voter.vote_count as voter_vote_count')
            ->where('election_voters.done', 1)
            ->leftJoin('voter', 'voter.id', 'answers.voter_id')
            ->leftJoin('election_voters', 'election_voters.voter_id', 'voter.id');
    }

    public function voters()
    {
        return $this->hasMany('App\Voter', 'voter_id');
    }
}
