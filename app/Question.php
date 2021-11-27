<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Question extends Model
{
    //
    protected $fillable = [
        'ques','no','election_id','image'
    ];

    public function QuesSummary($election_id, $question_id = null)
    {        
        if($question_id != 'null')
        {
            $result = DB::table('questions')
                ->leftjoin('answers', 'questions.id', '=', 'answers.ques_id')
                ->where('questions.election_id', $election_id)
                ->select('questions.id as question_id', 'questions.ques as question_title', 'questions.des', 'questions.image', DB::raw("COUNT(CASE answers.ans_flag WHEN '1' THEN 1 ELSE NULL END) AS yes_ans"), DB::raw("COUNT(CASE answers.ans_flag WHEN '0' THEN 1 ELSE NULL END) AS no_ans"))
                ->groupBy('questions.id')
                ->orderBy('questions.id', 'asc')
                ->get();        
           
        }else{            
            $result = DB::table('questions')
                ->leftjoin('answers', 'questions.id', '=', 'answers.ques_id')
                ->where('questions.election_id', $election_id)
                ->where('questions.id',$question_id)
                ->select('questions.id as question_id', 'questions.ques as question_title', 'questions.des', 'questions.image', DB::raw("COUNT(CASE answers.ans_flag WHEN '1' THEN 1 ELSE NULL END) AS yes_ans"), DB::raw("COUNT(CASE answers.ans_flag WHEN '0' THEN 1 ELSE NULL END) AS no_ans"))
                ->groupBy('questions.id')
                ->orderBy('questions.id', 'asc')
                ->first();
        }
        return $result;
    }
}
