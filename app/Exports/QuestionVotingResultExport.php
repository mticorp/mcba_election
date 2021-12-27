<?php

namespace App\Exports;

use App\Answer;
use App\ElectionVoter;
use App\Question;
use App\Voter;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class QuestionVotingResultExport implements FromView
{

    public function __construct($election_id)
    {
        $this->election_id = $election_id;
    }

    public function view(): View
    {
        $totalVotedShareAmount = 0;
        $questions = Question::with(['answers'])->where('election_id', $this->election_id)->get();
        foreach ($questions as $question) {
            $yes_count = 0;
            $no_count = 0;
            foreach ($question->answers as $answer) {
                $totalVoteCount =  $answer->voter_vote_count;
                if ($answer->ans_flag == 1) {
                    $yes_count += $totalVoteCount;
                } else {
                    $no_count += $totalVoteCount;
                }
            }
            $question->yes_count = $yes_count;
            $question->no_count = $no_count;
            $totalVotedShareAmount += $yes_count + $no_count;
        }


        $totalQuestionsCount = count($questions);
        $totalVoteCount = Voter::all()->sum('vote_count');        

        $totalVoter = ElectionVoter::all()->count();
        $totalVotedVoter = ElectionVoter::where('done', 1)->count();

        return view('admin.voting.voting_result_xls', [
            'questions' => $questions,
            'totalVotedShareAmount' => $totalVotedShareAmount,
            'totalSharedAmount' => $totalVoteCount,
            'totalVoter' => $totalVoter,
            'totalVotedVoter' => $totalVotedVoter,
        ]);
    }
}
