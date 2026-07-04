<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée.']);
    exit;
}

// Honeypot: ce champ est invisible pour un visiteur humain, seuls les robots le remplissent.
if (!empty($_POST['website'] ?? '')) {
    echo json_encode(['success' => true]);
    exit;
}

function clean_field(string $value): string
{
    // Supprime les retours à la ligne pour empêcher l'injection d'en-têtes email.
    return trim(str_replace(["\r", "\n"], '', $value));
}

$prenom    = clean_field($_POST['prenom'] ?? '');
$nom       = clean_field($_POST['nom'] ?? '');
$email     = clean_field($_POST['email'] ?? '');
$telephone = clean_field($_POST['telephone'] ?? '');
$sujet     = clean_field($_POST['sujet'] ?? '');
$message   = trim($_POST['message'] ?? '');

if ($prenom === '' || $nom === '' || $sujet === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => "Merci de compléter tous les champs obligatoires avec une adresse email valide.",
    ]);
    exit;
}

$destinataire = 'thierry.vaillant@78151.notaires.fr';
// Adresse du domaine du site : évite que le message soit classé comme spam
// (les hébergeurs comme OVH filtrent souvent les "From" qui n'appartiennent pas au domaine).
$expediteur = 'contact@vaillant-poissy.notaires.fr';

$sujetMail = 'Nouveau message du site — ' . $sujet;

$corps = "Nouvelle demande reçue depuis le formulaire de contact du site.\n\n"
    . "Prénom : {$prenom}\n"
    . "Nom : {$nom}\n"
    . "Email : {$email}\n"
    . "Téléphone : " . ($telephone !== '' ? $telephone : 'Non renseigné') . "\n"
    . "Objet : {$sujet}\n\n"
    . "Message :\n" . ($message !== '' ? $message : 'Non renseigné') . "\n";

$headers = "From: Site Office Notarial <{$expediteur}>\r\n"
    . "Reply-To: {$prenom} {$nom} <{$email}>\r\n"
    . "Content-Type: text/plain; charset=UTF-8";

$envoye = mail($destinataire, $sujetMail, $corps, $headers);

if ($envoye) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => "L'envoi a échoué. Merci de nous contacter directement par téléphone ou email.",
    ]);
}
