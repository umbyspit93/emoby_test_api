<?php
include "config/config.php";
include "controller.php";
$action = $_REQUEST['action'];

if ($action == "getUsersData") {
    $token = $_POST['token'];

    $emobyUsersList = curl_init();
    curl_setopt($emobyUsersList,CURLOPT_URL,"https://prod-api.emoby.it/user?token=".$token);
    curl_setopt($emobyUsersList,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($emobyUsersList,CURLOPT_HEADER, false); 
    curl_setopt($emobyUsersList, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($emobyUsersList, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($emobyUsersList, CURLOPT_POST, false);

    $users_list = curl_exec($emobyUsersList);
    $data_array = json_decode($users_list,true);
    curl_close($emobyUsersList);
    
    $binds = array();
    $set_arr = array();
    foreach ($data_array['user'] as $key => $val) {
        if ($key!="tickets") {
            $key_bind = ":".$key;
            $binds[$key_bind] = $val;
            $set_arr[] = $key."=:".$key;
        }
    }

    /*AVREI VOLUTO BINDARE I VALORI ED ESEGUIRE LA QUERY IN PDO TRAMITE PREPARE
    PER RENDERLA ANZITUTTO PIU' LEGGIBILE, IL CODICE SI TROVA IN __ajax.php 
    LA QUERY SI ESEGUE, LO STATEMENT DI PDO NON RESTITUISCE ERRORI, IL DUMP PARAMS
    MI SEMBRA CORRETTO, MA NONOSTANTE QUESTO NESSUN RECORD VIENE SALVATO IN TABELLA USANDO QUEL METODO*/

    $sql = "INSERT INTO user SET 
    id='".$binds[':id']."', 
    nome='".$binds[':nome']."', 
    cognome='".$binds[':cognome']."', 
    indirizzo='".$binds[':indirizzo']."', 
    cap='".$binds[':cap']."', 
    localita='".$binds[':localita']."', 
    prov='".$binds[':prov']."', 
    country='".$binds[':country']."', 
    tax_number='".$binds[':tax_number']."', 
    utente='".$binds[':utente']."', 
    token='".$binds[':token']."', 
    push_token='".$binds[':push_token']."', 
    push_id='".$binds[':push_id']."', 
    id_badge='".$binds[':id_badge']."', 
    num_badge='".$binds[':num_badge']."', 
    credito='".$binds[':credito']."', 
    email='".$binds[':email']."', 
    dati_cc='".$binds[':dati_cc']."', 
    payment_policy='".$binds[':payment_policy']."', 
    cell='".$binds[':cell']."', 
    verified=".$binds[':verified'].", 
    payment_account='".$binds[':payment_account']."', 
    can_rent=".$binds[':can_rent'].", 
    is_active='".$binds[':is_active']."', 
    can_rent_free=".$binds[':can_rent_free'].", 
    can_book=".$binds[':can_book'].", 
    id_azienda='".$binds[':id_azienda']."', 
    gender='".$binds[':gender']."', 
    is_tester='".$binds[':is_tester']."', 
    privacy='".$binds[':privacy']."', 
    privacy_mktg='".$binds[':privacy_mktg']."', 
    privacy_external='".$binds[':privacy_external']."', 
    privacy_stats='".$binds[':privacy_stats']."', 
    custom1='".$binds[':custom1']."',
    custom2='".$binds[':custom2']."', 
    custom3='".$binds[':custom3']."', 
    custom4='".$binds[':custom4']."', 
    custom5='".$binds[':custom5']."', 
    fare_code='".$binds[':fare_code']."', 
    ticket_id='".$binds[':ticket_id']."', 
    created_at='".$binds[':created_at']."', 
    updated_at='".$binds[':updated_at']."', 
    stripe_card_accepted='".$binds[':stripe_card_accepted']."', 
    biometric='".$binds[':biometric']."', 
    ttl='".$binds[':ttl']."', 
    sessioni='".$binds[':sessioni']."'";
    $stmt = $dbConn->query($sql);
}

if ($action == "reloadTable") {

    $user_list = getUsers();
    if (count($user_list) > 0) {
        foreach ($user_list as $value) {
        ?>
        <tr>
        <?php
        foreach (getTableColumns() as $field) {
            ?>
            <td><?=$value[$field['Field']]?></td>
            <?php
        }
        ?>
        </tr>
        <?php
        }
    } else {
        ?>
        <tr>
        <td colspan="<?=count(getTableColumns())?>" class="text-center">Nessun utente trovato</td>
        <tr>
        <?php
    }
}

?>