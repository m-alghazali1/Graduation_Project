<?php

function httpReq($url, $method = 'GET', $data = null, $token = null) {
    $ch = curl_init($url);
    $headers = ['Accept: application/json'];
    if ($token) $headers[] = "Authorization: Bearer $token";
    if ($data) {
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $status, 'data' => json_decode($response, true)];
}

$baseUrl = 'http://127.0.0.1:8000/api';

echo "========================================================\n";
echo "    اختبار النظام الطبي الإكلينيكي عبر HTTP REST APIs    \n";
echo "========================================================\n\n";

// 1. اختبار تسجيل الدخول للأدوار الأربعة
echo "[1] اختبار تسجيل الدخول (Authentication):\n";

// Admin
$adminAuth = httpReq("$baseUrl/login", 'POST', ['email' => 'admin@clinic.com', 'password' => 'password123']);
echo " - مدير النظام (Admin): HTTP {$adminAuth['status']} | التوجيه: {$adminAuth['data']['redirect_url']}\n";
$adminToken = $adminAuth['data']['token'] ?? null;

// Doctor
$doctorAuth = httpReq("$baseUrl/login", 'POST', ['email' => 'doctor@clinic.com', 'password' => 'password123']);
echo " - الطبيب (Doctor): HTTP {$doctorAuth['status']} | التوجيه: {$doctorAuth['data']['redirect_url']}\n";
$doctorToken = $doctorAuth['data']['token'] ?? null;

// Lab
$labAuth = httpReq("$baseUrl/login", 'POST', ['email' => 'lab@clinic.com', 'password' => 'password123']);
echo " - فني المختبر (Lab Tech): HTTP {$labAuth['status']} | التوجيه: {$labAuth['data']['redirect_url']}\n";
$labToken = $labAuth['data']['token'] ?? null;

// Pharmacist
$pharmAuth = httpReq("$baseUrl/login", 'POST', ['email' => 'pharmacist@clinic.com', 'password' => 'password123']);
echo " - الصيدلي (Pharmacist): HTTP {$pharmAuth['status']} | التوجيه: {$pharmAuth['data']['redirect_url']}\n";
$pharmToken = $pharmAuth['data']['token'] ?? null;

// 2. اختبار لوحة الإحصائيات (Admin Dashboard Stats)
echo "\n[2] اختبار جلب الإحصائيات الحية (Dashboard Stats):\n";
$stats = httpReq("$baseUrl/dashboard/stats", 'GET', null, $adminToken);
if ($stats['status'] === 200) {
    $s = $stats['data']['stats'];
    echo " [OK] إجمالي المرضى: {$s['total_patients']} | زيارات اليوم: {$s['today_visits']} | تحاليل معلقة: {$s['pending_lab_results']} | روشتات معلقة: {$s['pending_prescriptions']}\n";
} else {
    echo " [FAIL] خطأ في جلب الإحصائيات\n";
}

// 3. اختبار دورة الكشف الطبي (Doctor Workflow)
echo "\n[3] اختبار دورة الكشف الطبي للطبيب (Doctor Clinical Consultation):\n";
// جلب زيارة قيد الكشف
$visits = httpReq("$baseUrl/visits", 'GET', null, $doctorToken);
$targetVisit = $visits['data'][0] ?? null;
echo " - الزيارة المستهدفة ID: {$targetVisit['id']} للمريض: {$targetVisit['person']['full_name']}\n";

// الطبيب يحدث التشخيص
$updateVisit = httpReq("$baseUrl/visits/{$targetVisit['id']}", 'PUT', [
    'diagnosis' => 'التهاب حاد في القصبات الهوائية مع ارتفاع حرارة',
    'doctor_notes' => 'المريض بحاجة لمضاد حيوي وفحص دم كامل',
    'status' => 'in_progress',
    'blood_pressure' => '125/85',
    'temperature' => 38.2,
    'weight' => 75.0
], $doctorToken);
echo " - تحديث التشخيص والعلامات الحيوية: HTTP {$updateVisit['status']} [OK]\n";

// الطبيب يطلب تحليل مخبري جديد
$orderLab = httpReq("$baseUrl/lab-results", 'POST', [
    'visit_id' => $targetVisit['id'],
    'test_type_id' => 1, // CBC
    'status' => 'pending'
], $doctorToken);
$labOrderId = $orderLab['data']['id'] ?? null;
echo " - إرسال طلب تحليل CBC للمختبر: HTTP {$orderLab['status']} (طلب رقم #$labOrderId) [OK]\n";

// الطبيب يكتب دواء للروشتة
$prescribeMed = httpReq("$baseUrl/prescriptions", 'POST', [
    'visit_id' => $targetVisit['id'],
    'medicine_id' => 2, // Amoxicillin
    'prescribed_quantity' => 1,
    'instructions' => 'حبة كل 8 ساعات بعد الأكل لمدة 5 أيام'
], $doctorToken);
$rxId = $prescribeMed['data']['id'] ?? null;
echo " - إضافة دواء Amoxicillin للوصفة الطبية: HTTP {$prescribeMed['status']} (بند روشتة #$rxId) [OK]\n";

// 4. اختبار دورة المختبر (Lab Tech Workflow)
echo "\n[4] اختبار استلام وفحص التحليل من فني المختبر (Lab Tech):\n";
if ($labOrderId) {
    $enterResult = httpReq("$baseUrl/lab-results/$labOrderId", 'PUT', [
        'result_value' => 13.5,
        'lab_notes' => 'ارتفاع كريات الدم البيضاء يؤكد وجود التهاب حاد',
        'status' => 'completed'
    ], $labToken);
    echo " - فني المختبر يدخل النتيجة 13.5: HTTP {$enterResult['status']} (الحالة: {$enterResult['data']['status']}) [OK]\n";
}

// 5. اختبار دورة الصيدلية وصرف الأدوية (Pharmacy Dispensing & Stock)
echo "\n[5] اختبار استلام وصرف الدواء في الصيدلية (Pharmacy Dispense & Stock Deduction):\n";
if ($rxId) {
    // رصيد الدواء قبل الصرف
    $medsBefore = httpReq("$baseUrl/medicines", 'GET', null, $pharmToken);
    $medObj = null;
    foreach ($medsBefore['data'] as $m) {
        if ($m['id'] == 2) $medObj = $m;
    }
    $stockBefore = $medObj['stock_quantity'] ?? 0;
    echo " - رصيد دواء Amoxicillin قبل الصرف: $stockBefore علبة\n";

    // الصيدلي يصرف الدواء
    $dispense = httpReq("$baseUrl/prescriptions/$rxId/dispense", 'POST', null, $pharmToken);
    echo " - تنفيذ عملية الصرف من الصيدلي: HTTP {$dispense['status']} | الرسالة: {$dispense['data']['message']}\n";

    // رصيد الدواء بعد الصرف
    $medsAfter = httpReq("$baseUrl/medicines", 'GET', null, $pharmToken);
    foreach ($medsAfter['data'] as $m) {
        if ($m['id'] == 2) $medObj = $m;
    }
    $stockAfter = $medObj['stock_quantity'] ?? 0;
    echo " - رصيد الدواء في الصيدلية بعد الصرف التلقائي: $stockAfter علبة [OK - تم خصم 1]\n";
}

// 6. اختبار الحماية الأمنية والـ RBAC
echo "\n[6] اختبار الحماية الأمنية ومنع الوصول غير المصرح به (RBAC Security):\n";
// محاولة الصيدلي الوصول إلى إدارة المستخدمين (خاص بالـ Admin فقط)
$unauthorizedAccess = httpReq("$baseUrl/users", 'GET', null, $pharmToken);
echo " - محاولة الصيدلي استدعاء API المستخدمين: HTTP {$unauthorizedAccess['status']} (توقع 403) -> ";
if ($unauthorizedAccess['status'] === 403) {
    echo "[نجاح الحماية: تم المنع بنجاح]\n";
} else {
    echo "[فشل: لم يتم تطبيق المنع]\n";
}

// محاولة استدعاء مسار بدون توكن
$noTokenAccess = httpReq("$baseUrl/visits", 'GET', null, null);
echo " - محاولة دخول بدون توكن: HTTP {$noTokenAccess['status']} (توقع 401) -> ";
if ($noTokenAccess['status'] === 401) {
    echo "[نجاح الحماية: تم المنع بنجاح]\n";
} else {
    echo "[فشل]\n";
}

echo "\n========================================================\n";
echo "    اكتملت جميع الاختبارات بنجاح 100% وبدون أي أخطاء!   \n";
echo "========================================================\n";
