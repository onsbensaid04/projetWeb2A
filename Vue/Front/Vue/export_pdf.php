<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../../controller/candidature_con.php';
require_once __DIR__ . '/../../../controller/offre_emploi_con.php';
require_once __DIR__ . '/../../../model/candidature.php';
require_once __DIR__ . '/../../../model/offre_emploi.php';

// Use a popular PDF library
require_once __DIR__ . '/../../../vendor/autoload.php'; // mPDF assumed installed via Composer

session_start();
$user_id = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 1;

if (!isset($_GET['offre_id'])) {
    die('Missing offer ID');
}
$offre_id = intval($_GET['offre_id']);

$candidatureCon = new CandidatureCon();
$candidature = $candidatureCon->getByUserAndOffre($user_id, $offre_id);
if (!$candidature) {
    die('No application found.');
}

// Fetch user details
$userData = $candidatureCon->getCandidatureDataById($user_id);

$offreCon = new OffreEmploiCon();
$offre = $offreCon->getOne($offre_id);
if (!$offre) {
    die('No job offer found.');
}

// Prepare PDF content
$lettre = nl2br(htmlspecialchars($candidature['lettre_motivation']));
// Embed logo as base64
$logoPath = __DIR__ . '../assets/images/logo.png';
$logoData = '';
if (file_exists($logoPath)) {
    $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
    $logoBase64 = base64_encode(file_get_contents($logoPath));
    $logoData = 'data:image/' . $logoType . ';base64,' . $logoBase64;
}
$html = '<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
body { font-family: Poppins, Arial, sans-serif; color: #222; background: #f4f6fb; margin: 0; }
.pdf-container { max-width: 800px; margin: 40px auto; background: #fff; border-radius: 18px; box-shadow: 0 8px 40px rgba(0,0,0,0.10); overflow: hidden; position: relative; }
.sidebar { position: absolute; left: 0; top: 0; bottom: 0; width: 60px; background: linear-gradient(180deg, #ed563b 0%, #ff8c5a 100%); }
.logo-area { height: 60px; display: flex; align-items: center; justify-content: center; background: #fff; border-bottom: 1px solid #eee; }
.logo-circle { width: 38px; height: 38px; background: #ed563b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold; font-size: 1.3em; }
.header { margin-left: 60px; background: #ed563b; color: #fff; padding: 32px 40px 24px 40px; border-radius: 0 0 24px 0; position: relative; }
.title { font-size: 2.3em; font-weight: 800; margin-bottom: 8px; letter-spacing: 0.5px; }
.subtitle { font-size: 1.25em; margin-bottom: 18px; font-weight: 500; }
.section-divider { height: 2px; background: linear-gradient(90deg, #ed563b 0%, #ff8c5a 100%); border: none; margin: 32px 0 24px 0; }
.section { margin: 0 0 18px 0; }
.label { color: #ed563b; font-weight: 700; letter-spacing: 0.2px; }
.card { background: #f9f9f9; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); padding: 28px 36px; margin: 24px 0; margin-left: 60px; position: relative; }
.motivation { background: #fff7f4; border-left: 8px solid #ed563b; padding: 24px 30px; border-radius: 10px; font-size: 1.13em; font-style: italic; color: #333; }
.user-details { margin-bottom: 24px; display: flex; flex-wrap: wrap; gap: 0 32px; }
.user-details .pair { width: 48%; display: flex; align-items: baseline; margin-bottom: 10px; }
.user-details .label { display: inline-block; min-width: 90px; margin-right: 8px; }
.job-details { display: flex; flex-wrap: wrap; gap: 0 32px; }
.job-details .pair { width: 48%; display: flex; align-items: baseline; margin-bottom: 10px; }
.job-details .label { display: inline-block; min-width: 90px; margin-right: 8px; }
.watermark { position: absolute; bottom: 30px; right: 30px; opacity: 0.08; font-size: 4em; font-weight: 900; color: #ed563b; pointer-events: none; z-index: 0; }
@media (max-width: 900px) { .pdf-container { margin: 10px; } .header, .card { padding: 18px 12px 18px 24px; } .sidebar { width: 36px; } .logo-area { height: 36px; } .logo-circle { width: 24px; height: 24px; font-size: 1em; } .user-details .pair, .job-details .pair { width: 100%; } }
</style>
</head>
<body>
<div class="pdf-container">
  <div class="sidebar">
    <div class="logo-area">'.($logoData ? '<img src="'.$logoData.'" alt="Startup Academy Logo" style="height:38px;width:auto;display:block;" />' : '').'</div>
  </div>
  <div class="header">
    <div class="title">Startup Academy</div>
    <div class="subtitle">'.htmlspecialchars($offre['titre']).' at '.htmlspecialchars($offre['entreprise']).'</div>
  </div>
  <hr class="section-divider">
  <div class="card user-details" style="padding:0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="border:none;">
      <tr>
        <td style="vertical-align:top; width:50%; padding:18px 24px;">
          <div class="pair"><div class="label">Name:</div> '.htmlspecialchars($userData['prenom'].' '.$userData['nom']).'</div>
          <div class="pair"><div class="label">Email:</div> '.htmlspecialchars($userData['email']).'</div>
          <div class="pair"><div class="label">Phone:</div> '.htmlspecialchars($userData['telephone']).'</div>
        </td>
        <td style="vertical-align:top; width:50%; padding:18px 24px;">
          <div class="pair"><div class="label">Gender:</div> '.htmlspecialchars($userData['genre']).'</div>
          <div class="pair"><div class="label">Registered:</div> '.htmlspecialchars($userData['date_inscription']).'</div>
        </td>
      </tr>
    </table>
  </div>
  <div class="card job-details" style="padding:0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="border:none;">
      <tr>
        <td style="vertical-align:top; width:50%; padding:18px 24px;">
          <div class="pair"><span class="label">Position:</span> '.htmlspecialchars($offre['titre']).'</div>
          <div class="pair"><span class="label">Company:</span> '.htmlspecialchars($offre['entreprise']).'</div>
          <div class="pair"><span class="label">Location:</span> '.htmlspecialchars($offre['lieu']).'</div>
          <div class="pair"><span class="label">Salary:</span> '.htmlspecialchars($offre['salaire']).' TND</div>
        </td>
        <td style="vertical-align:top; width:50%; padding:18px 24px;">
          <div class="pair" style="width:100%"><span class="label">Description:</span> '.nl2br(htmlspecialchars($offre['description'])).'</div>
          <div class="pair"><span class="label">Date:</span> '.htmlspecialchars($candidature['date']).'</div>
        </td>
      </tr>
    </table>
  </div>
  <div class="card">
    <div class="label" style="font-size:1.2em;margin-bottom:10px;">Motivation Letter</div>
    <div class="motivation">'.$lettre.'</div>
  </div>
  <div class="watermark">Startup Academy</div>
</div>
</body>
</html>';

$mpdf = new \Mpdf\Mpdf(['default_font' => 'Poppins']);
$mpdf->SetTitle('Job Application - '.htmlspecialchars($offre['titre']));
$mpdf->WriteHTML($html);
$filename = 'Application_'.preg_replace('/[^a-zA-Z0-9]/','_', $offre['titre']).'_'.date('Ymd').'.pdf';
// Force download and open in new tab
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="'.$filename.'"');
$mpdf->Output($filename, \Mpdf\Output\Destination::DOWNLOAD);
flush();

exit;