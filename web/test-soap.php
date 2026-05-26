<?php
// Karena dijalankan lewat terminal, kita paku URL-nya ke localhost port server lu
$wsdl = "http://127.0.0.1:8080/soap/index?wsdl"; // Pastikan port 8080 sesuai dengan 'php yii serve' lu

try {
    // Inisialisasi SOAP Client dengan fitur Trace aktif
    $client = new SoapClient($wsdl, [
        'cache_wsdl' => WSDL_CACHE_NONE,
        'trace' => 1,
        'connection_timeout' => 5,
    ]);

    // Eksekusi fungsi SOAP
    $hasil = $client->getLogAktivitas(1, 'admin');

    // OUTPUT KHUSUS TAMPILAN TERMINAL
    echo "\n======================================================\n";
    echo " 🕵️‍♂️ SOAP API Trace - Log Aktivitas LAJARUS (CLI Mode)\n";
    echo "======================================================\n\n";

    echo "[🔴 BENTUK REQUEST (XML yang dikirim ke Server)]\n";
    echo $client->__getLastRequest() . "\n\n";

    echo "[🟢 BENTUK RESPONSE (XML balasan dari Server)]\n";
    echo $client->__getLastResponse() . "\n\n";

    echo "[📦 ISI PAYLOAD (Data JSON yang diekstrak)]\n";
    $dataArray = json_decode($hasil, true);
    echo json_encode($dataArray, JSON_PRETTY_PRINT) . "\n\n";
    echo "======================================================\n\n";

} catch (Exception $e) {
    echo "\n[❌ ERROR SOAP]\n" . $e->getMessage() . "\n";
}
?>