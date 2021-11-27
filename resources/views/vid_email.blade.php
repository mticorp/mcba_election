
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> </title>
    <style>

.btn{
    text-decoration: none;
    background-color:blue;
    color:white!important;
    cursor: pointer;
    padding: 10px 10px;
}

hr{
    margin-top: 30px;
}
    </style>
</head>
<body>

    <div>
        <h2>{!! $content !!}</h2>
    <br>
    <!--<a href="{{$link}}" style="text-decoration: none;-->
    <!--background-color:blue;-->
    <!--color:white!important; -->
    <!--cursor: pointer;-->
    <!--padding: 10px 10px;">Click here to Vote</a>-->
    <table cellpadding="0" cellmargin="0" border="0" height="44" width="178" style="border-collapse: collapse; border:5px solid #c62228">
  <tr>
    <td bgcolor="#c62228" valign="middle" align="center" width="174">
      <div style="font-size: 18px; color: #ffffff; line-height: 1; margin: 0; padding: 0; mso-table-lspace:0; mso-table-rspace:0;">
        <a href="{{$link}}" style="text-decoration: none; color: #ffffff; border: 0; font-family: Arial, arial, sans-serif; mso-table-lspace:0; mso-table-rspace:0;" border="0">Click here</a>
      </div>
    </td>
  </tr>
</table>
    <hr>
    <p>Bld:16, Room 602, Myanmar Info Tech, Hlaing Campus, Yangon, Myanmar.</p>
    <p>Mobile: 01-230 5213</p>
    <p>E-mail: office@mti.com.mm</p>
    <p>website: www.mti.com.mm</p>
    <img src="{{$message->embed($image)}}" style="width: 40%" alt="MTI">
    <br>
    <b>{{ $date }}</b>
    <b> / {{ $time }}</b>
    </div>

</body>
</html>
