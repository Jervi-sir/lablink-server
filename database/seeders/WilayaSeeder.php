<?php

namespace Database\Seeders;

use App\Models\Wilaya;
use Illuminate\Database\Seeder;

class WilayaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wilayas = [
            ['number' => '01', 'code' => 'adrar', 'en' => 'Adrar', 'fr' => 'Adrar', 'ar' => 'أدرار'],
            ['number' => '02', 'code' => 'chlef', 'en' => 'Chlef', 'fr' => 'Chlef', 'ar' => 'الشلف'],
            ['number' => '03', 'code' => 'laghouat', 'en' => 'Laghouat', 'fr' => 'Laghouat', 'ar' => 'الأغواط'],
            ['number' => '04', 'code' => 'oum_el_bouaghi', 'en' => 'Oum El Bouaghi', 'fr' => 'Oum El Bouaghi', 'ar' => 'أم البواقي'],
            ['number' => '05', 'code' => 'batna', 'en' => 'Batna', 'fr' => 'Batna', 'ar' => 'باتنة'],
            ['number' => '06', 'code' => 'bejaia', 'en' => 'Béjaïa', 'fr' => 'Béjaïa', 'ar' => 'بجاية'],
            ['number' => '07', 'code' => 'biskra', 'en' => 'Biskra', 'fr' => 'Biskra', 'ar' => 'بسكرة'],
            ['number' => '08', 'code' => 'bechar', 'en' => 'Béchar', 'fr' => 'Béchar', 'ar' => 'بشار'],
            ['number' => '09', 'code' => 'blida', 'en' => 'Blida', 'fr' => 'Blida', 'ar' => 'البليدة'],
            ['number' => '10', 'code' => 'bouira', 'en' => 'Bouira', 'fr' => 'Bouira', 'ar' => 'البويرة'],
            ['number' => '11', 'code' => 'tamanrasset', 'en' => 'Tamanrasset', 'fr' => 'Tamanrasset', 'ar' => 'تمنراست'],
            ['number' => '12', 'code' => 'tebessa', 'en' => 'Tébessa', 'fr' => 'Tébessa', 'ar' => 'تبسة'],
            ['number' => '13', 'code' => 'tlemcen', 'en' => 'Tlemcen', 'fr' => 'Tlemcen', 'ar' => 'تلمسان'],
            ['number' => '14', 'code' => 'tiaret', 'en' => 'Tiaret', 'fr' => 'Tiaret', 'ar' => 'تيارت'],
            ['number' => '15', 'code' => 'tizi_ouzou', 'en' => 'Tizi Ouzou', 'fr' => 'Tizi Ouzou', 'ar' => 'تيزي وزو'],
            ['number' => '16', 'code' => 'algiers', 'en' => 'Algiers', 'fr' => 'Alger', 'ar' => 'الجزائر'],
            ['number' => '17', 'code' => 'djelfa', 'en' => 'Djelfa', 'fr' => 'Djelfa', 'ar' => 'الجلفة'],
            ['number' => '18', 'code' => 'jijel', 'en' => 'Jijel', 'fr' => 'Jijel', 'ar' => 'جيجل'],
            ['number' => '19', 'code' => 'setif', 'en' => 'Sétif', 'fr' => 'Sétif', 'ar' => 'سطيف'],
            ['number' => '20', 'code' => 'saida', 'en' => 'Saïda', 'fr' => 'Saïda', 'ar' => 'سعيدة'],
            ['number' => '21', 'code' => 'skikda', 'en' => 'Skikda', 'fr' => 'Skikda', 'ar' => 'سكيكدة'],
            ['number' => '22', 'code' => 'sidi_bel_abbes', 'en' => 'Sidi Bel Abbès', 'fr' => 'Sidi Bel Abbès', 'ar' => 'سيدي بلعباس'],
            ['number' => '23', 'code' => 'annaba', 'en' => 'Annaba', 'fr' => 'Annaba', 'ar' => 'عنابة'],
            ['number' => '24', 'code' => 'guelma', 'en' => 'Guelma', 'fr' => 'Guelma', 'ar' => 'قالمة'],
            ['number' => '25', 'code' => 'constantine', 'en' => 'Constantine', 'fr' => 'Constantine', 'ar' => 'قسنطينة'],
            ['number' => '26', 'code' => 'medea', 'en' => 'Médéa', 'fr' => 'Médéa', 'ar' => 'المدية'],
            ['number' => '27', 'code' => 'mostaganem', 'en' => 'Mostaganem', 'fr' => 'Mostaganem', 'ar' => 'مستغانم'],
            ['number' => '28', 'code' => 'msila', 'en' => "M'Sila", 'fr' => "M'Sila", 'ar' => 'المسيلة'],
            ['number' => '29', 'code' => 'mascara', 'en' => 'Mascara', 'fr' => 'Mascara', 'ar' => 'معسكر'],
            ['number' => '30', 'code' => 'ouargla', 'en' => 'Ouargla', 'fr' => 'Ouargla', 'ar' => 'ورقلة'],
            ['number' => '31', 'code' => 'oran', 'en' => 'Oran', 'fr' => 'Oran', 'ar' => 'وهران'],
            ['number' => '32', 'code' => 'el_bayadh', 'en' => 'El Bayadh', 'fr' => 'El Bayadh', 'ar' => 'البيض'],
            ['number' => '33', 'code' => 'illizi', 'en' => 'Illizi', 'fr' => 'Illizi', 'ar' => 'إليزي'],
            ['number' => '34', 'code' => 'bordj_bou_arreridj', 'en' => 'Bordj Bou Arréridj', 'fr' => 'Bordj Bou Arréridj', 'ar' => 'برج بوعريريج'],
            ['number' => '35', 'code' => 'boumerdes', 'en' => 'Boumerdès', 'fr' => 'Boumerdès', 'ar' => 'بومرداس'],
            ['number' => '36', 'code' => 'el_tarf', 'en' => 'El Tarf', 'fr' => 'El Tarf', 'ar' => 'الطارف'],
            ['number' => '37', 'code' => 'tindouf', 'en' => 'Tindouf', 'fr' => 'Tindouf', 'ar' => 'تندوف'],
            ['number' => '38', 'code' => 'tissemsilt', 'en' => 'Tissemsilt', 'fr' => 'Tissemsilt', 'ar' => 'تسمسيلت'],
            ['number' => '39', 'code' => 'el_oued', 'en' => 'El Oued', 'fr' => 'El Oued', 'ar' => 'الوادي'],
            ['number' => '40', 'code' => 'khenchela', 'en' => 'Khenchela', 'fr' => 'Khenchela', 'ar' => 'خنشلة'],
            ['number' => '41', 'code' => 'souk_ahras', 'en' => 'Souk Ahras', 'fr' => 'Souk Ahras', 'ar' => 'سوق أهراس'],
            ['number' => '42', 'code' => 'tipaza', 'en' => 'Tipaza', 'fr' => 'Tipaza', 'ar' => 'تيبازة'],
            ['number' => '43', 'code' => 'mila', 'en' => 'Mila', 'fr' => 'Mila', 'ar' => 'ميلة'],
            ['number' => '44', 'code' => 'ain_defla', 'en' => 'Aïn Defla', 'fr' => 'Aïn Defla', 'ar' => 'عين الدفلى'],
            ['number' => '45', 'code' => 'naama', 'en' => 'Naâma', 'fr' => 'Naâma', 'ar' => 'النعامة'],
            ['number' => '46', 'code' => 'ain_temouchent', 'en' => 'Aïn Témouchent', 'fr' => 'Aïn Témouchent', 'ar' => 'عين تموشنت'],
            ['number' => '47', 'code' => 'ghardaia', 'en' => 'Ghardaïa', 'fr' => 'Ghardaïa', 'ar' => 'غرداية'],
            ['number' => '48', 'code' => 'relizane', 'en' => 'Relizane', 'fr' => 'Relizane', 'ar' => 'غليزان'],
            ['number' => '49', 'code' => 'timimoun', 'en' => 'Timimoun', 'fr' => 'Timimoun', 'ar' => 'تيميمون'],
            ['number' => '50', 'code' => 'bordj_badji_mokhtar', 'en' => 'Bordj Badji Mokhtar', 'fr' => 'Bordj Badji Mokhtar', 'ar' => 'برج باجي مختار'],
            ['number' => '51', 'code' => 'ouled_djellal', 'en' => 'Ouled Djellal', 'fr' => 'Ouled Djellal', 'ar' => 'أولاد جلال'],
            ['number' => '52', 'code' => 'beni_abbes', 'en' => 'Béni Abbès', 'fr' => 'Béni Abbès', 'ar' => 'بني عباس'],
            ['number' => '53', 'code' => 'in_salah', 'en' => 'In Salah', 'fr' => 'In Salah', 'ar' => 'عين صالح'],
            ['number' => '54', 'code' => 'in_guezzam', 'en' => 'In Guezzam', 'fr' => 'In Guezzam', 'ar' => 'عين قزام'],
            ['number' => '55', 'code' => 'touggourt', 'en' => 'Touggourt', 'fr' => 'Touggourt', 'ar' => 'تقرت'],
            ['number' => '56', 'code' => 'djanet', 'en' => 'Djanet', 'fr' => 'Djanet', 'ar' => 'جانت'],
            ['number' => '57', 'code' => 'el_mghair', 'en' => "El M'Ghair", 'fr' => "El M'Ghair", 'ar' => 'المغير'],
            ['number' => '58', 'code' => 'el_meniaa', 'en' => 'El Meniaa', 'fr' => 'El Meniaa', 'ar' => 'المنيعة'],
            ['number' => '59', 'code' => 'aflou', 'en' => 'Aflou', 'fr' => 'Aflou', 'ar' => 'أفلو'],
            ['number' => '60', 'code' => 'barika', 'en' => 'Barika', 'fr' => 'Barika', 'ar' => 'بريكة'],
            ['number' => '61', 'code' => 'ksar_chellala', 'en' => 'Ksar Chellala', 'fr' => 'Ksar Chellala', 'ar' => 'قصر الشلالة'],
            ['number' => '62', 'code' => 'messaad', 'en' => 'Messaad', 'fr' => 'Messaad', 'ar' => 'مسعد'],
            ['number' => '63', 'code' => 'ain_oussera', 'en' => 'Aïn Oussera', 'fr' => 'Aïn Oussera', 'ar' => 'عين وسارة'],
            ['number' => '64', 'code' => 'boussaada', 'en' => 'Boussaâda', 'fr' => 'Boussaâda', 'ar' => 'بوسعادة'],
            ['number' => '65', 'code' => 'el_abiodh_sidi_cheikh', 'en' => 'El Abiodh Sidi Cheikh', 'fr' => 'El Abiodh Sidi Cheikh', 'ar' => 'البيض سيدي الشيخ'],
            ['number' => '66', 'code' => 'el_kantara', 'en' => 'El Kantara', 'fr' => 'El Kantara', 'ar' => 'القنطرة'],
            ['number' => '67', 'code' => 'bir_el_ater', 'en' => 'Bir El Ater', 'fr' => 'Bir El Ater', 'ar' => 'بئر العاتر'],
            ['number' => '68', 'code' => 'ksar_el_boukhari', 'en' => 'Ksar El Boukhari', 'fr' => 'Ksar El Boukhari', 'ar' => 'قصر البخاري'],
            ['number' => '69', 'code' => 'el_aricha', 'en' => 'El Aricha', 'fr' => 'El Aricha', 'ar' => 'العريشة'],
        ];

        foreach ($wilayas as $wilaya) {
            Wilaya::create($wilaya);
        }
    }
}
