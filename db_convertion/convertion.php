<?php

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}


$servername = "localhost";
$username = "root";
$password = "";
$olddbname = "orario_scuola";
$dbname = "school_resources";

// Crea le connessioni
$conn_old_db = new mysqli($servername, $username, $password, $olddbname);

$conn_new_db = new mysqli($servername, $username, $password, $dbname);






//
// CONVERSIONE CLASSI
//

$sql = "SELECT * FROM classi";
$result = $conn_old_db->query($sql);
$classID = array();
$className = array();
$i = 0;
while($row = $result->fetch_assoc()){
    $classID[$i] = $row["idclasse"];
    $className[$i] = $row['nomeclasse'];
    $i++;
}
for($i = 0; $i < sizeof($classID); $i++){
    $name = $className[$i];
    $sql = "INSERT INTO class (id, name, creation) VALUES ($i, '$name', now())";
    //$result = $conn_new_db->query($sql);
}





//
// CONVERSIONE DOCENTI
//

$sql = "SELECT * FROM docenti ORDER BY iddocente";
$result = $conn_old_db->query($sql);
$teacherID = array();
$teacherSurname = array();
$i = 0;
while($row = $result->fetch_assoc()){
    $teacherID[$i] = $row["iddocente"];
    $teacherSurname[$i] = $row['nomedocente'];
    $i++;
}
$teacherEmail = ['michele.allocca@itiszuccante.edu.it', 'filippo.andreato@itiszuccante.edu.it', 'michele.arienti@itiszuccante.edu.it', 'simone.belcastro@itiszuccante.edu.it', 
                 'carmine.belloisi@itiszuccante.edu.it', 'gilberto.berlese@itiszuccante.edu.it', 'alessandra.bessega@itiszuccante.edu.it', 'luigi.brandi@itiszuccante.edu.it',
                 'alberto.bulzatti@itiszuccante.edu.it', 'salvatore.calcara@itiszuccante.edu.it', 'daniele.cappellazzo@itiszuccante.edu.it', 'cristiano.capuzzo@itiszuccante.edu.it', 
                 'tiziana.carotenuto@itiszuccante.edu.it', 'valeria.conedera@itiszuccante.edu.it', 'samuele.crivellaro@itiszuccante.edu.it', 'fabio.deste@itiszuccante.edu.it',
                 'francesco.deste@itiszuccante.edu.it', 'alessandro.decesco@itiszuccante.edu.it', 'mariarosaria.deluca@itiszuccante.edu.it', 'lorenzo.denardi@itiszuccante.edu.it', 
                 'elga.derossi@itiszuccante.edu.it', 'lorenzo.derossi21@itiszuccante.edu.it', 'eros.delgiudice@itiszuccante.edu.it', 'daniele.dentico@itiszuccante.edu.it', 
                 'mariaconcetta.dipietro@itiszuccante.edu.it', 'susanna.dittadi@itiszuccante.edu.it', 'francesco.domingo@itiszuccante.edu.it', 'giuseppe.donzella@itiszuccante.edu.it', 
                 'giulia.ferrari@itiszuccante.edu.it', 'cinzia.filoni@itiszuccante.edu.it', 'roberta.folli@itiszuccante.edu.it', 'giuseppe.gambino@itiszuccante.edu.it', 
                 'giordano.ghezzo@itiszuccante.edu.it', 'federico.giacomazzi@itiszuccante.edu.it', 'stefano.giacomello@itiszuccante.edu.it', 'simonetta.giacomin@itiszuccante.edu.it', 
                 'silvia.giantin@itiszuccante.edu.it', 'giaquinto.aniello@itiszuccante.edu.it', 'sandra.girotto@itiszuccante.edu.it', 'gunternils.gottardo@itiszuccante.edu.it', 
                 'daniela.guglielmi@itiszuccante.edu.it', 'pietro.guido@itiszuccante.edu.it', 'alessandra.gusso@itiszuccante.edu.it', 'mariateresa.ieraci@itiszuccante.edu.it', 
                 'francesca.lacamera@itiszuccante.edu.it', 'alessandro.liberalato@itiszuccante.edu.it', 'luca.livieri@itiszuccante.edu.it', 'elisa.macchion@itiszuccante.edu.it', 
                 'gianpietro.manente@itiszuccante.edu.it', 'marco.manfrin@itiszuccante.edu.it', 'eva.manica@itiszuccante.edu.it', 'fabio.manna@itiszuccante.edu.it', 
                 'giovanna.marra@itiszuccante.edu.it', 'stella.martorelli@itiszuccante.edu.it', 'cristiano.massaro@itiszuccante.edu.it', 'sergio.mattiello@itiszuccante.edu.it', 
                 'lorena.meli@itiszuccante.edu.it', 'stefania.menegazzi@itiszuccante.edu.it', 'andrea.michielan@itiszuccante.edu.it', 'elena.micich@itiszuccante.edu.it', 
                 'alessandra.milani@itiszuccante.edu.it', 'enrico.minosso@itiszuccante.edu.it', 'giovanni.morelli@itiszuccante.edu.it', 'andrea.morettin@itiszuccante.edu.it', 
                 'antonella.nicolini@itiszuccante.edu.it', 'barbara.niero@itiszuccante.edu.it', 'roberto.orlando@itiszuccante.edu.it', 'paola.padoan@itiszuccante.edu.it', 
                 'caterina.pagan@itiszuccante.edu.it', 'sara.pagone@itiszuccante.edu.it', 'federica.paiaro@itiszuccante.edu.it', 'andrea.paladin@itiszuccante.edu.it', 
                 'francesco.papasidero@itiszuccante.edu.it', 'marilena.pasqualetto@itiszuccante.edu.it', 'diego.pavan@itiszuccante.edu.it', 'francesco.pavanello@itiszuccante.edu.it', 
                 'goffredo.perisbulighin@itiszuccante.edu.it', 'paola.pernice@itiszuccante.edu.it', 'federica.perocco@itiszuccante.edu.it', 'claudio.peron@itiszuccante.edu.it', 
                 'filippo.pigozzo@itiszuccante.edu.it', 'daniela.quici@itiszuccante.edu.it', 'cristina.rimoldi@itiszuccante.edu.it', 'alviseantonio.rizzi@itiszuccante.edu.it', 
                 'giancarlo.ronchi@itiszuccante.edu.it', 'matteopio.ruggiero@itiszuccante.edu.it', 'sara.rullo@itiszuccante.edu.it', 'fabiana.saccon@itiszuccante.edu.it', 
                 'massimo.sammartino@itiszuccante.edu.it', 'pietro.santagati@itiszuccante.edu.it', 'agata.scanselli@itiszuccante.edu.it', 'michele.schimd@itiszuccante.edu.it', 
                 'simona.scopelliti@itiszuccante.edu.it', 'claudia.serantoni@itiszuccante.edu.it', 'susanna.stelluto@itiszuccante.edu.it', 'vitoantonio.tanzi@itiszuccante.edu.it', 
                 'maurizio.trevisan@itiszuccante.edu.it', 'alessandro.vecchiato@itiszuccante.edu.it', 'alberto.vettorato@itiszuccante.edu.it', 'marco.vianello57@itiszuccante.edu.it', 
                 'mariagrazia.viel@itiszuccante.edu.it', 'graziella.viglietti@itiszuccante.edu.it', 'giuseppina.vittoria@itiszuccante.edu.it', 'davide.zambelli@itiszuccante.edu.it', 
                 'lara.zanardo@itiszuccante.edu.it', 'mauro.zane@itiszuccante.edu.it', 'francesco.zanzo@itiszuccante.edu.it', 'nicola.zocco@itiszuccante.edu.it'];
$teacherName = ['Michele', 'Filippo', 'Michele', 'Simone', 'Carmine', 'Gilberto', 'Alessandra', 'Luigi', 'Andrea', 'Salvatore', 'Daniele', 'Cristiano', 'Tiziana', 'Valeria', 
                'Samuele', 'Fabio', 'Francesco', 'Alessandro', 'Mariarosaria', 'Lorenzo', 'Elga', 'Lorenzo', 'Eros', 'Daniele', 'Maria Concetta', 'Susanna', 'Francesco', 'Giuseppe', 
                'Giulia', 'Cinzia', 'Roberta', 'Giuseppe', 'Giordano', 'Federico', 'Stefano', 'Simonetta', 'Silvia', 'Aniello', 'Sandra', 'Gunter Nils', 'Daniela', 'Pietro', 
                'Alessandra', 'Maristella', 'Francesca', 'Alessandro', 'Luca', 'Elisa', 'Gianpietro', 'Marco', 'Eva', 'Fabio', 'Giovanna', 'Stella', 'Cristiano', 'Sergio', 
                'Lorena', 'Stefania', 'Andrea', 'Elena', 'Alessandra', 'Enrico', 'Giovanni', 'Andrea', 'Antonella', 'Barbara', 'Roberto', 'Paola', 'Caterina', 'Sara', 'Federica', 
                'Andrea', 'Francesco', 'Marilena', 'Diego', 'Francesco', 'Goffredo', 'Paola', 'Federica', 'Claudio', 'Filippo', 'Daniela', 'Cristina', 'Alvise Antonio', 'Giancarlo',
                'Matteo Pio', 'Sara', 'Fabiana', 'Massimo', 'Pietro', 'Agata', 'Michele', 'Simona', 'Claudia', 'Susanna', 'Vito Antonio', 'Maurizio', 'Alessandro', 'Alberto', 
                'Marco', 'Mariagrazia', 'Graziella', 'Giuseppina', 'Davide', 'Lara', 'Mauro', 'Francesco', 'Nicola'];
$passwords = array();                
for($i = 0; $i < sizeof($teacherID); $i++){
    $surname = $teacherSurname[$i];
    $name = $teacherName[$i];
    $email = $teacherEmail[$i];
    $passwords[$i] = generateRandomString(10);
    $passwordTh = hash('sha256', $passwords[$i]);
    $stmt = $conn_new_db->prepare("INSERT INTO users (email, name, surname, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $name, $surname, $passwordTh);
    //$stmt->execute();
    $sql = "INSERT INTO teacher (id, user_id) VALUES ('$i', '$email')";
    //$result = $conn_new_db->query($sql);
}
print_r($passwords);





//
// CREAZIONE SCHEDULE_TIME
//

$days_time = [6,6,6,6,6,4];
for($i = 0; $i < 6; $i++){
    for($j = 0; $j < 6; $j++){
        $id = ($i*6) + $j;
        //$sql = "INSERT INTO schedule_time (id, day, hour) VALUES ($id, $i, $j)";
        //$result = $conn_new_db->query($sql);
    }
}

function oldTimeToNew($day, $hour){
    if(($day*6)+$hour > 5){
        $id = $hour;
        while($day>0){
            $id += 6;
            $day--;
        }
        return $id;
    }else{
        return $hour;
    }
}





//
// CONVERSIONE AULE
//

$sqlClasses = "SELECT * FROM classi";
$resultClasses = $conn_old_db->query($sqlClasses);

$sql = "SELECT DISTINCT aule.idaula, aule.nomeaula, sedi.nomesede FROM aule
        INNER JOIN aulelezioni ON aule.idaula = aulelezioni.idaula
        INNER JOIN lezioni ON aulelezioni.idlezione = lezioni.idlezione
        INNER JOIN sedi ON sedi.idsede = lezioni.idsede
        ORDER BY aule.idaula ASC";
$result = $conn_old_db->query($sql);
$roomID = array();
$roomName = array();
$siteName = array();
$i = 0;

while($row = $result->fetch_assoc()){
    $roomID[$i] = $row["idaula"];
    $roomName[$i] = $row['nomeaula'];
    if((($row['nomeaula'] == 'PAL.H.')) || ($row['nomeaula'] == 'PAL. T.')){
        $siteName[$i] = 1;
        $row = $result->fetch_assoc();
    }else{
        if($row['nomesede'] == 'TRIENNIO'){
            $siteName[$i] = 1;
        }else{
            $siteName[$i] = 0;
        }
    }
    $i++;
}

while($row = $resultClasses->fetch_assoc()){
    $roomID[$i] = $i;
    $roomName[$i] = $row['nomeclasse'];
    $siteName[$i] = 0;
    $i++;
}

for($i = 0; $i < sizeof($roomID); $i++){
    $name = $roomName[$i];
    $site = $siteName[$i];
    $sql = "INSERT INTO room (id, name, site) VALUES ($i, '$name', $site)";
    $result = $conn_new_db->query($sql);
}

echo 'romms converted';



//
// CONVERSIONE SCHEDULE
//


$sql = "SELECT L.idlezione, L.durata, L.idgiorno, L.idora, M.nomemateria, D.iddocente, A.idaula, C.idclasse FROM lezioni AS L 
        INNER JOIN materie AS M ON L.idmateria = M.idmateria 
        INNER JOIN docentilezioni AS D ON D.idlezione = L.idlezione 
        INNER JOIN classilezioni AS C ON C.idlezione = L.idlezione
        LEFT JOIN aulelezioni AS A ON  A.idlezione = L.idlezione
        ORDER BY L.idlezione ASC";
$result = $conn_old_db->query($sql);
$scheduleID = array();
$scheduleTeacher = array();
$scheduleClass = array();
$scheduleDuration = array();
$scheduleRoom = array();
$scheduleSubject = array();
$scheduleTime = array();
$i = 0;
while($row = $result->fetch_assoc()){
    $scheduleID[$i] = $row["idlezione"];
    $scheduleClass[$i] = $row['idclasse'];
    $scheduleTeacher[$i] = $row['iddocente'];
    $scheduleRoom[$i] = $row['idaula'];
    $scheduleSubject[$i] = $row['nomemateria'];
    $scheduleDuration[$i] = $row['durata'];
    $scheduleTime[$i] = oldTimeToNew($row["idgiorno"], $row["idora"]);
    $i++;
}
for($i = 0; $i < sizeof($scheduleID); $i++){
    $subject = $scheduleSubject[$i];
    $class = array_search($scheduleClass[$i], $classID);
    $teacher = array_search($scheduleTeacher[$i], $teacherID);
    if($scheduleRoom[$i] === NULL){
        $roomIDC = array_search($className[$class], $roomName);
        $room = array_search($roomIDC, $roomID);
    }else{
        $room = array_search($scheduleRoom[$i], $roomID);
    }
    $time = $scheduleTime[$i];
    if($scheduleDuration[$i] > 60){
        while($scheduleDuration[$i] > 0){
            $sql = "INSERT INTO schedule (subject, class_id, teacher_id, schedule_time_id, room_id) VALUES ('$subject', $class, $teacher, $time, $room)";
            $result = $conn_new_db->query($sql);
            $time++;
            $scheduleDuration[$i] = $scheduleDuration[$i] - 60;
        }
    }else{
        $sql = "INSERT INTO schedule (subject, class_id, teacher_id, schedule_time_id, room_id) VALUES ('$subject', $class, $teacher, $time, $room)";
        $result = $conn_new_db->query($sql);
    }
}









$conn_new_db->close();
$conn_old_db->close();
?>