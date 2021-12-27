<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Answer extends Model
{
    //
    protected $fillable = [
        'voter_id', 'ques_id', 'ans_flag'
    ];

    public function answerDetail($election_id)
    {
        $array = [];
        $voters = DB::table('answers')
            ->select('answers.*', 'voter.id as id', 'voter.voter_id as voter_id', 'election_voters.done as done')
            ->leftjoin('questions', 'questions.id', '=', 'answers.ques_id')
            ->leftjoin('voter', 'voter.id', '=', 'answers.voter_id')
            ->leftjoin('election_voters', 'voter.id', '=', 'election_voters.voter_id')
            ->where('questions.election_id', '=', $election_id)
            ->where('election_voters.done', '=', 1)
            ->get();
        // dd($voters);
        foreach ($voters as $voter) {
            $result = $this->getAnswerResult($voter->id, $election_id);
            $array[$voter->voter_id] = $result;
        }
        // dd($array);
        return $array;
    }

    public function getAnswerResult($voter_id, $election_id)
    {
        $result = DB::table('answers')
            ->leftjoin(DB::raw("(select * from questions where election_id ='" . $election_id . "')as questions"), 'questions.id', '=', 'answers.ques_id')
            ->select('answers.id', 'answers.voter_id', DB::raw("(CASE answers.ans_flag WHEN '0' THEN 'No' ELSE 'Yes' END)as ans"))
            ->where('answers.voter_id', $voter_id)
            ->where('questions.election_id', $election_id)
            ->orderBy('answers.ques_id', 'asc')
            ->get();
        // dd($result);
        return $result;
    }

    public function AnswerSummary($election_id)
    {
        $result = DB::table('answers')
            ->leftjoin('questions', 'questions.id', '=', 'answers.ques_id')
            ->where('questions.election_id', $election_id)
            ->select('answers.ques_id as question_id', DB::raw("COUNT(CASE answers.ans_flag WHEN '1' THEN 1 ELSE NULL END) AS yes_ans"), DB::raw("COUNT(CASE answers.ans_flag WHEN '0' THEN 1 ELSE NULL END) AS no_ans"))
            ->groupBy('answers.ques_id')
            ->orderBy('answers.ques_id', 'asc')
            ->get();
        // dd($result);
        return $result;
    }

    public function AnswerSummaryWithQuestionName($election_id)
    {
        // $result = DB::table('answers')
        //          ->leftjoin('questions','questions.id','=','answers.ques_id')
        //          ->where('questions.election_id',$election_id)
        //          ->select('answers.ques_id as question_id','questions.ques','questions.no',DB::raw("COUNT(CASE answers.ans_flag WHEN '1' THEN 1 ELSE NULL END) AS yes_ans"),DB::raw("COUNT(CASE answers.ans_flag WHEN '0' THEN 1 ELSE NULL END) AS no_ans"))
        //          ->groupBy('answers.ques_id','questions.ques','questions.no')
        //          ->orderBy('answers.ques_id', 'asc')
        //          ->get();
        // dd($result);

        $ques_list = Question::select('id', 'no', 'ques')->where('election_id', $election_id)->get();

        $answer_result = DB::table('answers')
            ->leftjoin('questions', 'questions.id', '=', 'answers.ques_id')
            ->where('questions.election_id', $election_id)
            ->select('answers.ques_id as question_id', DB::raw("COUNT(CASE answers.ans_flag WHEN '1' THEN 1 ELSE NULL END) AS yes_ans"), DB::raw("COUNT(CASE answers.ans_flag WHEN '0' THEN 1 ELSE NULL END) AS no_ans"))
            ->groupBy('answers.ques_id')
            ->orderBy('answers.ques_id', 'asc')
            ->get();
        // dd($answer_result);
        foreach ($ques_list as $ques) {
            if (count($answer_result) > 0) {
                foreach ($answer_result as $ans) {
                    if ($ques->id == $ans->question_id) {
                        $ques->yes_ans = $ans->yes_ans;
                        $ques->no_ans = $ans->no_ans;
                    }
                }
            } else {
                $ques->yes_ans = 0;
                $ques->no_ans = 0;
            }
        }
        return $ques_list;
    }
}
