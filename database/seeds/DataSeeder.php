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
        $company = Company::create(['company_name' => 'Test Company', 'company_logo' => '/images/election_logo.png']);
        $election = Election::create([
            'name' => 'Election Voting',
            'election_title_description' => 'Director ရွေးချယ်ရန် သဘောထားရယူခြင်း',
            'candidate_flag' => 1,    
            'position' => 'Director',
            'description' => 'အများဆုံး (၁) ဦး ရွေးချယ်ပေးပါရန် -',
            'candidate_title' => 'ရွေးချယ်ခံ ကိုယ်စားလှယ်လောင်းများ၏ ကိုယ်ရေး အချက်အလက်များ',        
            'no_of_position_mm' => '၅',
            'no_of_position_en' => 5,
            'start_time' => '0000-00-00 00:00:00',
            'end_time' => '0000-00-00 00:00:00',
            'duration_from' => '2021-12-31 14:44:00',
            'duration_to' => '2021-12-31 20:44:00',
            'company_id' => $company->id,
        ]);        
    }
}
