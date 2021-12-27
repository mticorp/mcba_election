<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voting Result</title>
</head>

<body>
    <table>
        <tr>
            <th colspan="6" style="font-weight: bold; text-align: center; font-size: 16px">
                MCB Election Voting Result
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: center; padding: 15px;">Q-No</th>
            <th style="font-weight: bold; text-align: center; padding: 15px;">Question</th>
            <th style="font-weight: bold; text-align: center; padding: 15px;">Yes</th>
            <th style="font-weight: bold; text-align: center; padding: 15px;">No</th>
            <th style="font-weight: bold; text-align: center; padding: 15px;" colspan="2">Voted Share</th>
        </tr>
        @foreach ($questions as $question)
            <tr>
                <td style="text-align: center">{{ $question->no }}</td>
                <td>{!! $question->ques !!}</td>
                <td style="text-align: right">{{ $question->yes_count }}</td>
                <td style="text-align: right">{{ $question->no_count }}</td>
                <td style="text-align: right" colspan="2">{{ $question->yes_count + $question->no_count }}</td>
            </tr>
        @endforeach
        <tr></tr>
        <tr>
            <td colspan="4">Total Share Amount</td>
            <td style="text-align: right" colspan="2">{{ $totalSharedAmount }}</td>
        </tr>
        <tr>
            <td colspan="4">Total Voted Share Amount</td>
            <td style="text-align: right" colspan="2">{{ $totalVotedShareAmount }}</td>
        </tr>
        <tr>
            <td colspan="4">Percentage of Voted Share Amount</td>
            <td style="text-align: right" colspan="2">
                {{ number_format(($totalVotedShareAmount / $totalSharedAmount) * 100, 2) }}
                %</td>
        </tr>
        <tr>
            <td colspan="4">Total Voter</td>
            <td style="text-align: right" colspan="2">{{ $totalVoter }}</td>
        </tr>
        <tr>
            <td colspan="4">Total Voted Person</td>
            <td style="text-align: right" colspan="2">{{ $totalVotedVoter }}</td>
        </tr>
        <tr>
            <td colspan="4">Percentage of Voted Person</td>
            <td style="text-align: right" colspan="2">{{ number_format(($totalVotedVoter / $totalVoter) * 100, 2) }}
                %</td>
        </tr>
    </table>
</body>

</html>
