<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'currency_symbol' => 'Rp',
            'currency_position' => 'prefix',
            'decimal_places' => '0',
            'date_format' => 'd M Y',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
            'smtp_host' => 'smtp.mailtrap.io',
            'email_template_header' => 'Dear Valued Client,',
            'email_template_footer' => 'Best regards, J&J GROUP Team',
            'primary_color' => '#6366f1',
            'company_name' => 'J&J GROUP PLUMBING SERVICES',
            'company_email' => 'Jayarooter@gmail.com / Jawarooter@gmail.com',
            'company_address' => 'Jl. Dewa RT.002/002 No.70, Ciracas, Jakarta Timur',
            'company_phone' => '0812-40000-759 / 0812-40000-749 / 0812-83-300-900',
            'company_website' => 'Jayarooter.com / Jawarooter.com',
            'invoice_start_number' => '5000',
            'ppn_percent' => '',
            'pph_percent' => '',
            'last_backup_at' => null,
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
