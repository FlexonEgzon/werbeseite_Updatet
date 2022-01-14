<?php
use Illuminate\Database\Capsule\Manager as Capsule;
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/gericht.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/home_gerichte.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/bewertungen.php');
require_once('../vendor/autoload.php');


class HomeController
{
    public function index(RequestData $request) {
        $data1 =  db_gerichte();
        $data2 = db_allergene();
        //Eloquent
        $data3 = (new Bewertung)->db_hervorgehobenebewertungen();

        logger()->info('Zugriff auf die Hauptseite von einem unbekannten');

        return view('home', ['data1' => $data1, 'data2' => $data2, 'data3' => $data3]);
    }

    public function debug(RequestData $request) {
        return view('debug');
    }

    public function bewertung(RequestData $request){
        $data3 =  db_nameundbild();

        if ($_SESSION['loginOK'] == true){
            $_SESSION['gerichtid'] = $_GET['gerichtid'];
          return view('bewertung', ['data1' => $data3]);
          }
        else {
            $data1 =  db_gerichte();
            $data2 = db_allergene();
             return view('home', ['data1' => $data1, 'data2' => $data2]);
        }
    }

    public function bewertunghochladen(RequestData $request){

        $bemerkung = $request->query['bemerkung'];
        $gerichtid = $request->query['gerichtid'];
        if (empty($bemerkung) || strlen($bemerkung) < 5)
            header('Location: /bewertung?gerichtid=' . $gerichtid);
        else{
            $rank = $request->query['rank'];
            $userid = $_SESSION['userid'];
           bewertung_hochladen($rank,$userid,$bemerkung,$gerichtid);
            $data1 =  db_gerichte();
            $data2 = db_allergene();



            return view('home', ['data1' => $data1, 'data2' => $data2]);
        }
    }


        //Eloquent
    public function bewertungen(RequestData $request){
        return view('bewertungen', ['data1' => (new Bewertung())->db_bewertungen()]);
    }

    public function meinebewertungen(RequestData $request){
        $data1 =  db_meinebewertungen();
        return view('meinebewertungen', ['data1' => $data1]);
    }
        //Eloquent
    public function bewertungloeschen(RequestData $request){
        (new Bewertung)->bewertungloeschen($_GET['id']);
            $data1 = db_meinebewertungen();
            return view('meinebewertungen', ['data1' => $data1]);
    }
        //Eloquent
    public function bewertunghervorheben(RequestData $request){
        (new Bewertung)->bewertunghervorheben($_GET['id']);
        $data1 = db_bewertungen();
        header('Location: /bewertungen');
        return view('\bewertungen', ['data1' => $data1]);
    }


}