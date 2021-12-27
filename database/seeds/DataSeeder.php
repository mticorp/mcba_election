<?php

use App\Company;
use App\Election;
use App\Question;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::create(['company_name' => 'MCB', 'company_logo' => '/images/election_logo.png']);
        $election = Election::create([
            'name' => 'MCB Election',
            'election_title_description' => 'သဘောထားရယူခြင်း',
            'ques_flag' => 1,
            'ques_title' => 'MCB သဘောထားဆန္ဒပြုခြင်း',
            'ques_description' => 'MCB Bank  ဒါရိုက်တာအဖွဲ့၏ ၂၀၂၀-၂၀၂၁ ဘဏ္ဍာနှစ် အစီရင်ခံချက်နှင့် ဆွေးနွေးဆုံးဖြတ်ချက်များအပေါ် သဘောထားဆန္ဒပြုခြင်း',
            'start_time' => '0000-00-00 00:00:00',
            'end_time' => '0000-00-00 00:00:00',
            'duration_from' => '2021-12-31 14:44:00',
            'duration_to' => '2021-12-31 20:44:00',
            'company_id' => $company->id,
        ]);

        Question::create([
            'no' => '၁)',
            'ques' => '<p class="MsoNormal"><font face="Myanmar Text, sans-serif">၁၄-၁၂-၂၀၂၁ နေ့တွင် MCB ၏ Website https://www.mcb.com.mm တွင် ပေးပို့တင်ပြထားပြီးဖြစ်သည့် ၃၀-၉-၂၀၂၁ နေ့ ကုန်ဆုံးသော (၂၀၂၀-၂၀၂၁) ဘဏ္ဍာနှစ်အတွက် ဒါရိုက်တာအဖွဲ့၏ နှစ်ပတ်လည် အစီရင်ခံစာနှင့် ဘဏ္ဍာရေးရှင်းတမ်းအား အတည်ပြုရန် အစုရှယ်ယာရှင်များ၏ သဘောထားရယူခြင်း။</font></p><p class="MsoNormal"><font face="Myanmar Text, sans-serif">(၂၉) ကြိမ်မြောက် နှစ်ပတ်လည်အထွေထွေအစည်းအဝေးသို့ တင်ပြသည့် မြန်မာနိုင်ငံသားများဘဏ်လီမိတက် ဒါရိုက်တာအဖွဲ့၏ အစီရင်ခံစာအား အောက်ပါအတိုင်း ကြည့်ရှုလေ့လာနိုင်ပါသည်။</font></p><p class="MsoNormal"><a href="https://bit.ly/3sbU6Az" target="_blank">https://bit.ly/3sbU6Az</a><br></p>',
            'election_id' => $election->id,
        ]);

        Question::create([
            'no' => '၂)',
            'ques' => '<p class="MsoNormal"><font face="Myanmar Text, sans-serif">၂၀၂၁-၂၀၂၂ ခုနှစ်အတွက် အမှုဆောင်ဒါရိုက်တာ အဖွဲ့ဝင်များအဖြစ် ပြန်လည် ခန့်အပ်ခြင်းအား အတည်ပြုရန် အစုရှယ်ယာရှင်များ၏ သဘောထားရယူခြင်း။</font></p><p class="MsoNormal"><font face="Myanmar Text, sans-serif">ကုမ္ပဏီ၏ ဖွဲ့စည်းပုံ အခြေခံစည်းမျဉ်းအရ ၁၉-၁၂-၂၀၂၁ နေ့၌ ကျင်းပပြုလုပ်မည့် မြန်မာနိုင်ငံသားများဘဏ်လီမိတက် ၏ (၂၉) ကြိမ်မြောက် နှစ်ပတ်လည်အစည်းအဝေးတွင် ဒါရိုက်တာအဖွဲ့ဝင် (၁၀) ဦးဖြစ်သော <b>ဦးမင်းမင်း၊ ဦးတိုးအောင်မြင့်၊ ဦးကိုကိုကြီး၊ ဦးလှဦး၊ ဦးထွန်းလွင်၊ ဦးအုန်းဆိုင်၊ ဦးဇေယျသူရမွန်၊ ဦးရန်ပိုင်စိုးဦး၊ ဦးအောင်အောင်၊ Daw Cherry Trivedi</b></font></p><p class="MsoNormal"><font face="Myanmar Text, sans-serif">တို့ နှုတ်ထွက်ပေးခဲ့ပြီး ၂၀၂၁-၂၀၂၂ အတွက် လစ်လပ်သည့် ဒါရိုက်တာများနေရာတွင် သတ်မှတ်ချိန်အထိ ပြန်လည်အဆိုပြုသည့် ဒါရိုက်တာ (၉)ဦးဖြစ်သော<b> ဦးမင်းမင်း၊ ဦးတိုးအောင်မြင့်၊ ဦးကိုကိုကြီး၊ ဦးလှဦး၊ ဦးထွန်းလွင်၊ ဦးအုန်းဆိုင်၊ ဦးဇေယျသူရမွန်၊ ဦးအောင်အောင်၊ ဒေါ်ခင်မာမြင့်</b> တို့အား</font></p><p class="MsoNormal"><font face="Myanmar Text, sans-serif">၂၀၂၁-၂၀၂၂ ခုနှစ်အတွက် ဒါရိုက်တာ အဖွဲ့ဝင်အဖြစ် ခန့်အပ်ခြင်းအပေါ် အစုရှယ်ယာရှင်များ၏ သဘောထားရယူခြင်း။</font></p>',
            'election_id' => $election->id,
        ]);

        Question::create([
            'no' => '၃)',
            'ques' => '<p class="MsoNormal" style="text-align:justify"><font face="Myanmar Text, sans-serif">၂၀၂၁-၂၂ အတွက် တာဝန်ယူဆောင်ရွက်ရန် လွတ်လပ်သောဒါရိုက်တာ အဖြစ် ဦးအောင်စိုးအား ခန့်အပ်ရန် မြန်မာနိုင်ငံသားများဘဏ်လီမိတက် ၏ ဒါရိုက်တာအဖွဲ့မှ ဆွေးနွေးဆုံးဖြတ်ခဲ့ခြင်းအား အတည်ပြုရန် အစုရှယ်ယာရှင်များ၏ သဘောထားရယူခြင်း။</font></p><p class="MsoNormal" style="text-align:justify"><font face="Myanmar Text, sans-serif"><u><b>ဦးအောင်စိုး ၏ Profile</b></u></font></p><p class="MsoNormal" style="text-align:justify"><font face="Myanmar Text, sans-serif">ဦးအောင်စိုးသည်အငြိမ်းအစားအမြဲတန်းအတွင်းဝန်ဖြစ်ပြီးစီးပွားရေးနှင့်ကူးသန်းရောင်းဝယ်ရေး ဝန်ကြီးဌာနမှ ၂၀၂၀ ခုနှစ်တွင်အငြိမ်းစားယူခဲ့သည်။ ယခင်က ယင်းဝန်ကြီးဌာနမှ မြန်မာကုန်သွယ်မှု မြင့်တင်ရေး အဖွဲ့တွင် ညွှန်ကြားရေးမှူးချုပ်တာဝန်ထမ်းဆောင်ခဲ့သည်။</font></p><p class="MsoNormal" style="text-align:justify"><font face="Myanmar Text, sans-serif">စီးပွားရေးနှင့်ကူးသန်းရောင်းဝယ်ရေးဝန်ကြီးဌာ၌ ၁၉၉၅ ခုနှစ်တွင် စတင်တာဝန်ထမ်း ဆောင်ခဲ့ ပြီး စီမံကိန်းနှင့်စာရင်းအင်းဌာနခွဲ၊ ပို့ကုန်ဌာနခွဲ၊ အပြည်ပြည်ဆိုင်ရာကုန်သွယ်မှုမြှင့်တင်ရေးဌာနခွဲ များ တွင် ညွှန်ကြားရေးမှူးအဖြစ်လည်းကောင်း၊ ကုန်သွယ်မှုမြှင့်တင်ရေးဉီးစီးဌာနတွင် ဒုတိယညွှန်ကြားရေး မှူးချုပ်အဖြစ်လည်းကောင်းတာဝန်ထမ်းဆောင်ခဲ့သည်။<span style="white-space:pre">			</span></font></p><p class="MsoNormal" style="text-align:justify"><font face="Myanmar Text, sans-serif"><span style="white-space:pre">	</span>စီးပွားရေးနှင့်ကူးသန်းရောင်းဝယ်ရေး ဝန်ကြီးဌာနကတာဝန်ပေးအပ်သော အများသားပို့ကုန် မဟာဗျူဟာရေးဆွဲအကောင်အထည်ဖေါ်ရာတွင်ဉီးဆောင်လမ်းညွှန်သူအဖြစ် ၂၀၁၅ ခုနှစ်မှ ၂၀၁၉ ခုနစ်အထိလည်းကောင်း၊ ကုန်သွယ်မှုနှင့်စီးပွားရေးလုပ်ငန်းများမြှင့်တင်ရေးလုပ်ငန်းအဖွဲ့၏အတွင်းရေးမှူး၊ ပုဂ္ဂလိကကဏ္ဍဖွံ့ဖြိုးတိုးတက်ရေးကော်မတီ၏အတွင်းရေးမှူး၊ မြန်မာနိုင်ငံတွင် စီးပွားရေး လုပ်ငန်း များလုပ်ကိုင်ရန်လွယ်ကူရေးလုပ်ငန်းအဖွဲ့အတွင်းရေးမှူး၊ အထူးစီးပွားရေးဇုန်လုပ်ငန်းအဖွဲ့အတွင်းရေး မှူးတာဝန်များကိုလည်းကောင်း ထမ်းဆောင်ခဲ့သည်။</font></p><p class="MsoNormal" style="text-align:justify"><font face="Myanmar Text, sans-serif"><span style="white-space:pre">	</span>အာဆီယံလွတ်လပ်သောကုန်သွယ်မှုဒေသတည်ထောင်ရေးဆွေးနွေးညှိနှိုင်းပွဲများတွင် ကုန်စည် ကုန်သွယ်ရေးနှင့် ပင်ရင်းနိုင်ငံစည်းမျဉ်းကျွမ်းကျင်သူအဖြစ် မြန်မာနိုင်ငံကိုယ်စား၂၀၀၄ ခုနှစ်မှ ၂၀၀၇ ခုနှစ်အထိ ပါဝင်ဆွေးနွေးခဲ့သည်။&nbsp;&nbsp;</font></p><p class="MsoNormal" style="text-align:justify"><font face="Myanmar Text, sans-serif"><br></font></p><p class="MsoNormal" style="text-align:justify"><font face="Myanmar Text, sans-serif"><span style="white-space:pre">	</span>ပို့ကုန်သွင်းကုန်အကောက်ခွန်သက်သာခွင့်လမ်းညွှန်၊ ပင်ရင်းနိုင်ငံစည်းမျဉ်းနှင့် ပင်ရင်းနိုင်ငံ လက်မှတ် စာအုပ်ကို ၂၀၀၈ခု နှစ် တွင် ပြုစုထုတ်ဝေခဲ့သည်။ <span style="white-space:pre">		</span>မန္တလေးတက္ကသိုလ်တွင်ပညာသင်ခဲ့ပြီး ဝိဇ္ဇာဘွဲ့ကို စိတ်ပညာဘာသာရပ်အဓိကဖြင့်ရရှိခဲ့သည်။ <b>Lee kuan Yew Fellowship Program of National University of Singapore and Kennedy School of Government, Harvard University, USA</b> တို့တွင်ပညာသင်ကြားခဲ့ပြီး ပြည်သူ့ရေးရာစီမံခန့်ခွဲမှု မဟာ ဘွဲ့ (<b>Master in Public Management</b>) ကို ရရှိခဲ့သည်။&nbsp;</font></p>',
            'election_id' => $election->id,
        ]);

        Question::create([
            'no' => '၄)',
            'ques' => '<p class="MsoNormal"><font face="Myanmar Text, sans-serif">၂၀၂၁-၂၂ ဘဏ္ဍာနှစ် အတွက် ပြင်ပစာရင်းစစ်အဖြစ် V-Advisory အား ခန့်အပ်ခြင်းကို အတည်ပြုရန် အစုရှယ်ယာရှင်များ၏ သဘောထားရယူခြင်း။</font></p><div><br></div>',
            'election_id' => $election->id,
        ]);
    }
}
