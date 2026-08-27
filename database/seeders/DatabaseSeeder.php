<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Governorate;
use App\Models\City;
use App\Models\Neighborhood;
use App\Models\Person;
use App\Models\User;
use App\Models\TestType;
use App\Models\Medicine;
use App\Models\Visit;
use App\Models\LabResult;
use App\Models\PrescriptionItem;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. المحافظات (محافظات قطاع غزة)
        $gazaGov = Governorate::firstOrCreate(['name' => 'غزة']);
        $khanYounisGov = Governorate::firstOrCreate(['name' => 'خان يونس']);
        $rafahGov = Governorate::firstOrCreate(['name' => 'رفح']);

        // 2. المدن
        $cityGaza = City::firstOrCreate(['name' => 'مدينة غزة', 'governorate_id' => $gazaGov->id]);
        $cityKhanYounis = City::firstOrCreate(['name' => 'مدينة خان يونس', 'governorate_id' => $khanYounisGov->id]);
        $cityRafah = City::firstOrCreate(['name' => 'مدينة رفح', 'governorate_id' => $rafahGov->id]);

        // 3. الأحياء
        $n1 = Neighborhood::firstOrCreate(['name' => 'حي الرمال', 'city_id' => $cityGaza->id]);
        $n2 = Neighborhood::firstOrCreate(['name' => 'حي الشجاعية', 'city_id' => $cityGaza->id]);
        $n3 = Neighborhood::firstOrCreate(['name' => 'المنطقة الصناعية', 'city_id' => $cityKhanYounis->id]);
        $n4 = Neighborhood::firstOrCreate(['name' => 'حي تل السلطان', 'city_id' => $cityRafah->id]);

        // 4. أشخاص الكادر الطبي والإداري (ببيانات وأرقام هاتف فلسطينية)
        $personAdmin = Person::firstOrCreate(
            ['national_id' => '401234567'],
            [
                'full_name' => 'د. أحمد الغزالي (مدير المركز)',
                'phone' => '0599111222',
                'birth_date' => '1980-05-15',
                'gender' => 'male',
                'neighborhood_id' => $n1->id
            ]
        );

        $personDoctor = Person::firstOrCreate(
            ['national_id' => '402345678'],
            [
                'full_name' => 'د. سارة الشوا (طبيب عام / باطنة)',
                'phone' => '0598222333',
                'birth_date' => '1985-08-20',
                'gender' => 'female',
                'neighborhood_id' => $n2->id
            ]
        );

        $personLab = Person::firstOrCreate(
            ['national_id' => '403456789'],
            [
                'full_name' => 'أ. خالد النجار (فني مختبر)',
                'phone' => '0569333444',
                'birth_date' => '1990-11-10',
                'gender' => 'male',
                'neighborhood_id' => $n3->id
            ]
        );

        $personPharm = Person::firstOrCreate(
            ['national_id' => '404567890'],
            [
                'full_name' => 'د. ريم هنيّة (صيدلانية)',
                'phone' => '0597444555',
                'birth_date' => '1992-03-25',
                'gender' => 'female',
                'neighborhood_id' => $n4->id
            ]
        );

        // 5. حسابات المستخدمين بالأدوار الرسمية
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@clinic.com'],
            [
                'person_id' => $personAdmin->id,
                'password' => Hash::make('password123'),
                'role' => 'admin'
            ]
        );

        $doctorUser = User::firstOrCreate(
            ['email' => 'doctor@clinic.com'],
            [
                'person_id' => $personDoctor->id,
                'password' => Hash::make('password123'),
                'role' => 'doctor'
            ]
        );

        $labUser = User::firstOrCreate(
            ['email' => 'lab@clinic.com'],
            [
                'person_id' => $personLab->id,
                'password' => Hash::make('password123'),
                'role' => 'lab_employee'
            ]
        );

        $pharmUser = User::firstOrCreate(
            ['email' => 'pharmacist@clinic.com'],
            [
                'person_id' => $personPharm->id,
                'password' => Hash::make('password123'),
                'role' => 'pharmacist'
            ]
        );

        // 6. مرضى تجريبيين (بأسماء وهواتف فلسطينية واقعية)
        $patient1 = Person::firstOrCreate(
            ['national_id' => '801112223'],
            [
                'full_name' => 'محمد سمير الجيار',
                'phone' => '0599777666',
                'birth_date' => '1994-04-12',
                'gender' => 'male',
                'neighborhood_id' => $n1->id
            ]
        );

        $patient2 = Person::firstOrCreate(
            ['national_id' => '802223334'],
            [
                'full_name' => 'فاطمة عمر المناعمة',
                'phone' => '0568666555',
                'birth_date' => '1988-09-03',
                'gender' => 'female',
                'neighborhood_id' => $n2->id
            ]
        );

        $patient3 = Person::firstOrCreate(
            ['national_id' => '803334445'],
            [
                'full_name' => 'عمر عبد الرحمن الأغا',
                'phone' => '0592555444',
                'birth_date' => '2001-12-19',
                'gender' => 'male',
                'neighborhood_id' => $n3->id
            ]
        );

        // 7. أنواع الفحوصات والتحاليل الطبية
        $tCbc = TestType::firstOrCreate(
            ['name' => 'تعداد دم كامل (CBC)'],
            [
                'code' => 'CBC',
                'unit' => '10^3/uL',
                'min_range' => 4.00,
                'max_range' => 11.00,
                'price' => 15.00,
                'description' => 'فحص كريات الدم الحمراء والبيضاء والصفائح',
                'status' => 'active'
            ]
        );

        $tFbs = TestType::firstOrCreate(
            ['name' => 'سكر الدم الصيامي (FBS)'],
            [
                'code' => 'FBS',
                'unit' => 'mg/dL',
                'min_range' => 70.00,
                'max_range' => 100.00,
                'price' => 10.00,
                'description' => 'قياس مستوى السكر في الدم بعد صيام 8 ساعات',
                'status' => 'active'
            ]
        );

        $tChol = TestType::firstOrCreate(
            ['name' => 'الكوليسترول الكلي (Cholesterol)'],
            [
                'code' => 'CHOL',
                'unit' => 'mg/dL',
                'min_range' => 120.00,
                'max_range' => 200.00,
                'price' => 20.00,
                'description' => 'تحليل الدهون والكوليسترول الكلي في الدم',
                'status' => 'active'
            ]
        );

        $tCreat = TestType::firstOrCreate(
            ['name' => 'الكرياتينين - وظائف كلى (Creatinine)'],
            [
                'code' => 'CREAT',
                'unit' => 'mg/dL',
                'min_range' => 0.60,
                'max_range' => 1.20,
                'price' => 18.00,
                'description' => 'فحص كفاءة عمل الكليتين',
                'status' => 'active'
            ]
        );

        $tAlt = TestType::firstOrCreate(
            ['name' => 'إنزيمات الكبد (ALT/SGPT)'],
            [
                'code' => 'ALT',
                'unit' => 'U/L',
                'min_range' => 7.00,
                'max_range' => 56.00,
                'price' => 18.00,
                'description' => 'فحص وظائف وسلامة خلايا الكبد',
                'status' => 'active'
            ]
        );

        // 8. أنواع الأدوية
        $m1 = Medicine::firstOrCreate(
            ['name' => 'Panadol Extra 500mg'],
            [
                'strength' => '500mg',
                'stock_quantity' => 120,
                'is_available' => true
            ]
        );

        $m2 = Medicine::firstOrCreate(
            ['name' => 'Amoxicillin 500mg'],
            [
                'strength' => '500mg',
                'stock_quantity' => 60,
                'is_available' => true
            ]
        );

        $m3 = Medicine::firstOrCreate(
            ['name' => 'Augmentin 1g'],
            [
                'strength' => '1000mg',
                'stock_quantity' => 45,
                'is_available' => true
            ]
        );

        $m4 = Medicine::firstOrCreate(
            ['name' => 'Concor 5mg'],
            [
                'strength' => '5mg',
                'stock_quantity' => 50,
                'is_available' => true
            ]
        );

        $m5 = Medicine::firstOrCreate(
            ['name' => 'Glucophage 500mg'],
            [
                'strength' => '500mg',
                'stock_quantity' => 85,
                'is_available' => true
            ]
        );

        $m6 = Medicine::firstOrCreate(
            ['name' => 'Cetirizine 10mg'],
            [
                'strength' => '10mg',
                'stock_quantity' => 8,
                'is_available' => true
            ]
        );

        // 9. إنشاء زيارات سريرية توضيحية متكاملة لرحلة المريض
        $visit1 = Visit::firstOrCreate(
            ['person_id' => $patient1->id, 'appointment_date' => now()->subDays(1)->format('Y-m-d 10:00:00')],
            [
                'doctor_id' => $doctorUser->id,
                'blood_pressure' => '120/80',
                'weight' => 74.5,
                'temperature' => 37.1,
                'diagnosis' => 'التهاب قصبات حاد وسعال مستمر',
                'doctor_notes' => 'المريض يعاني من حرارة خفيفة وسعال منذ 3 أيام. تم طلب تحليل دم وصرف مضاد حيوي.',
                'status' => 'completed'
            ]
        );

        LabResult::firstOrCreate(
            ['visit_id' => $visit1->id, 'test_type_id' => $tCbc->id],
            [
                'result_value' => 12.8,
                'lab_notes' => 'ارتفاع طفيف في كريات الدم البيضاء يشير لعدوى بكتيرية',
                'status' => 'completed'
            ]
        );

        PrescriptionItem::firstOrCreate(
            ['visit_id' => $visit1->id, 'medicine_id' => $m3->id],
            [
                'dosage' => '1g',
                'prescribed_quantity' => 2,
                'instructions' => 'حبة كل 12 ساعة بعد الطعام لمدة 7 أيام',
                'is_dispensed' => true,
                'dispensed_at' => now()->subDays(1)->addHours(2)
            ]
        );

        $visit2 = Visit::firstOrCreate(
            ['person_id' => $patient2->id, 'appointment_date' => now()->format('Y-m-d 09:30:00')],
            [
                'doctor_id' => $doctorUser->id,
                'blood_pressure' => '145/95',
                'weight' => 68.0,
                'temperature' => 36.8,
                'diagnosis' => 'اشتباه ارتفاع ضغط الدم واضطراب دهون',
                'doctor_notes' => 'صداع مستمر ودوخة. تم تحويل المريضة للمختبر لفحص السكر والدهون، ووصف كونكور 5 ملغ.',
                'status' => 'in_progress'
            ]
        );

        LabResult::firstOrCreate(
            ['visit_id' => $visit2->id, 'test_type_id' => $tFbs->id],
            [
                'result_value' => null,
                'lab_notes' => null,
                'status' => 'pending'
            ]
        );

        LabResult::firstOrCreate(
            ['visit_id' => $visit2->id, 'test_type_id' => $tChol->id],
            [
                'result_value' => null,
                'lab_notes' => null,
                'status' => 'pending'
            ]
        );

        PrescriptionItem::firstOrCreate(
            ['visit_id' => $visit2->id, 'medicine_id' => $m4->id],
            [
                'dosage' => '5mg',
                'prescribed_quantity' => 1,
                'instructions' => 'حبة واحدة صباحاً قبل الإفطار يومياً',
                'is_dispensed' => false,
                'dispensed_at' => null
            ]
        );

        Visit::firstOrCreate(
            ['person_id' => $patient3->id, 'appointment_date' => now()->format('Y-m-d 11:15:00')],
            [
                'doctor_id' => $doctorUser->id,
                'blood_pressure' => '115/75',
                'weight' => 70.0,
                'temperature' => 37.0,
                'diagnosis' => null,
                'doctor_notes' => 'مراجعة دورية عامة',
                'status' => 'waiting'
            ]
        );
    }
}
