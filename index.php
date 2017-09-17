<?php
//TO DO LIST
// - dodać czytanie ile jest max kolumn
// -

ob_start();
require_once 'functions.php';
//max available uploaded file size in MB
$max_file_size = 1;

$kolumny = ((isset($_POST['kolumny']) && !empty($_POST['kolumny']))? $_POST['kolumny'] : 21);

//available extensions
$mimes = array('csv','txt');
$mimes_print = '';

//storing available extensions in var to display later
foreach($mimes as $ext){ $mimes_print .= $ext." ";}

//if the file exist
if (isset($_FILES['plik'])){  
    if ($_FILES['plik']['size'] <= ($max_file_size)*1024*1024){
        switch($_FILES['plik']['error']){
            case 0:{
                if(isset($_FILES['plik']['name']) && !empty($_FILES['plik']['name'])){
                    $uploaded_file_name = filter_var($_FILES['plik']['name'], FILTER_SANITIZE_STRING);
                    $uploaded_file_ext = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);   
                    if(in_array($uploaded_file_ext,$mimes) ) {

                        //wczytuje całą zawartośc do zmiennej i exploduje (dziele na tablice) ją po znaku konca linii
                        $content = explode("\n", file_get_contents($_FILES['plik']['tmp_name']));

                        //dodaje zmienna przechowujaca tablice
                        $tab = array();
                        
                        //usuwam znak konca lini z kazdego elementu w tablicy
                        $content = str_replace(array("\r", "\n"), '', $content);

                        //pętla która wykonuje coś dla każdego elementu w tablicy
                        foreach($content as $line){
                                //przypisuje do zmiennej exp zawartość elementów i exploduje po średnikach
                                $exp = explode(";", $line);
                                //przypisuje zmiennej $tab alias oraz zawartosc talicy exp
                                $tab[$exp[0]][] = $exp;
                        }
                        
                        $new_file = ((isset($_POST['new_file']) && !empty($_POST['new_file']))? (filter_var($_POST['new_file'], FILTER_SANITIZE_STRING)) : "min_".$uploaded_file_name);
                        
                        //jesli sosob minifikacji - domyślny
                        if(isset($_POST['domyslny'])){
                            if(isset($_POST['ilosc_powt']) && !empty($_POST['ilosc_powt']) && isset($_POST['ile_wierszy']) && !empty($_POST['ile_wierszy'])){
                                if($_POST['ilosc_powt'] >= $_POST['ile_wierszy']){
                                    domyslny($_POST['ilosc_powt'], $_POST['ile_wierszy'], $_POST['czy_wypisac_domain'], $kolumny, $tab, $_POST['czy_usunac_duplikaty'],$new_file);
                                }else{
                                    echo "<div class='alert alert-danger'>Ilość wystąpień zawartości nie może być mniejsza od ilości wierszy które chcesz wyświetlić!</div>";
                                }
                            }else{
                                domyslny(4, 1, TRUE, $kolumny, $tab, FALSE,$new_file);
                            }
                            

                        }else{
                            foreach ($tab as $item){
                                wypisz_wszystkie_wiersze_normalnie($item, $kolumny,$new_file);
                            }
                        }
                        //$zapis = trim($zapis,'');
                        //zapis do nowego pliku
                        //$f = fopen("php://memory", "w");
                        //fputcsv($f, array($zapis));
                        // reset the file pointer to the start of the file
                       // rewind($f);
                        // tell the browser it's going to be a csv file
                        //header('Content-Type: application/csv; charset=UTF-8');
                        // tell the browser we want to save it instead of displaying it
                        //$new_file = ((isset($_POST['new_file']) && !empty($_POST['new_file']))? (filter_var($_POST['new_file'], FILTER_SANITIZE_STRING)) : "min_".$uploaded_file_name);
                        //header('Content-Disposition: attachment;filename="'.$new_file.'";');
                        // make php send the generated csv lines to the browser
                       // fpassthru($f);
                        //fclose($f);
                       exit;

                    }else{
                        echo "<div class='alert alert-danger'>Niedozwolone rozszerzenie piku!</div>";
                        echo "<div class='alert alert-info'>Dozwolone rozszerzenia pliku: <strong>".$mimes_print."</strong>.</div>";
                    }
                }else{
                    echo "Plik który chcesz przesłać musi mieć nazwę!";
                }
//                echo "<div class='alert alert-success'>Wczytano plik: <strong>".$_FILES['plik']['name']."</strong>.<br>File size: <strong>".round((($_FILES['plik']['size'])/1024), 0)."</strong> KB</div>";
//                echo "<pre>";
//                echo var_dump($_FILES['plik']);
//                echo "</pre>";
//                break;
            }
            case 1:{
                echo "<div class='alert alert-danger'>Za duży plik (php.ini)";
            break;
            }
            case 2:{
                echo "<div class='alert alert-danger'>Zbyt duży plik <strong>".$max_file_size_uploaded."</strong> KB.</div>";
                echo "<div class='alert alert-info'>Dozwolona wielkość pliku: <strong>".$max_file_size."</strong> KB.</div>";
                break;
            }
            case 3:{
                echo "<div class='alert alert-danger'>Uszkodzony plik <strong>".$uploaded_file_name."</strong>.</div>";
                break;
            }
            case 4:{
                echo "<div class='alert alert-danger'>Nie wybrano pliku!</div>";
                break;
            }
            default:{
                echo "<div class='alert alert-danger'>Błąd!</div>";
            }
        }
     }else{
         echo "<div class='alert alert-danger'>Zbyt duży plik <strong>".$uploaded_file_name."</strong>.</div>";
         echo "<div class='alert alert-info'>Dozwolona wielkość pliku: <strong>".$max_file_size."</strong> KB.<br>Wielkość przesłanego pliku: <strong>".round((($_FILES['plik']['size'])/1024), 0)."</strong> KB</div>";
     }
     }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>.csv Minifier</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            .small {
                height: 0px;
                overflow:hidden;
            }
            .big {
                height: auto;
            }
            .wrapper a {
                float: right;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <form action="index.php" method="post" enctype="multipart/form-data">
                        
                            <input type="hidden" name="MAX_FILE_SIZE" value="<?php ($max_file_size)*1024;?>">
                            <div class="form-group ">
                                <label class="control-label " for="plik">
                                     Wybierz plik:
                                </label>
                                <input id="plik"  name="plik" type="file" required/>
                                <span class="help-block" id="hint_plik">
                                 .csv/ .txt
                                </span>
                            </div>                           
                            <div class="form-group row">
                                <div class="col-xs-4">
                                    <label class="control-label " for="kolumny">
                                     Ilość kolumn
                                    </label>
                                    <input class="form-control" id="kolumny" name="kolumny" type="number" min="1" max="100"/>
                                    <span class="help-block" id="hint_kolumny">
                                     domyślnie: 21
                                    </span>
                                </div>
                                <div class="col-xs-7">
                                    <label class="control-label " for="new_file">
                                        Nowa nazwa pliku
                                    </label>
                                    <div class="input-group">
                                        <input class="form-control" id="new_file" name="new_file" placeholder="przykładowa_nazwa.csv" pattern="[a-z0-9._%+-]+\.csv" type="text" title="np. przykładowa_nazwa.csv"/>
                                    </div>
                                    <span class="help-block" id="hint_new_file">
                                         domyślnie: min_(nazwa_pliku).csv
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-group ">
                                <label class="control-label ">
                                    Sposób minifikacji
                                </label>
                                 <div class="checkbox">
                                  <label class="checkbox">
                                   <input name="domyslny" type="checkbox" value="Domyślny" onclick="showMe('a1')"/>
                                   Domyślny
                                  </label>
                                 </div>
                                 <span class="help-block" id="hint_checkbox">
                                     <strong>Domyślny:</strong> Usuwa całe wiersze danych i pozostawia tylko <b>1</b> przykład z dopiskiem <strong>"domain:"</strong> wszędzie tam, gdzie z domeny prowadzą <strong>4</strong> linki lub więcej.
                                 <div class="wrapper">
                                    <div class="small">
                                        <div class="form-group row well">
                                            <div class="form-group row well">
                                                <strong>Ilość wystąpień:</strong> podajesz od ilu wystąpień zawartości pierwszej kolumny skrypt ma zacząc działać.<br>
                                                <strong>Ile wierszy:</strong> podajesz ile wierszy skrypt ma wyświetlić kiedy napotka powtarzający sie rekord.<br>
                                            </div>
                                            <div class="col-xs-4">
                                                <label class="control-label " for="ilosc_powt">
                                                 Ilość wystąpień 
                                                </label>
                                                <input class="form-control" id="ilosc_powt" name="ilosc_powt" type="number" min="1"/>
                                                <span class="help-block" id="hint_kolumny">
                                                 domyślnie: 4
                                                </span>
                                            </div>
                                            <div class="col-xs-4">
                                                <label class="control-label " for="ile_wierszy">
                                                 Ile wierszy
                                                </label>
                                                <input class="form-control" id="ile_wierszy" name="ile_wierszy" type="number" min="1"/>
                                                <span class="help-block" id="hint_kolumny">
                                                 domyślnie: 1
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group row well">
                                            <div>
                                                <label class="control-label " for="czy_wypisac_domain">
                                                    Czy wypisać <i>"domain:"</i> przy powtarzających się rekordach?
                                                </label>
                                                <select class="select form-control" id="czy_wypisac_domain" name="czy_wypisac_domain">
                                                    <option value="TRUE">Tak</option>
                                                    <option value="FALSE">Nie</option>
                                                </select>
                                                <span class="help-block" id="hint_czy_wypisac_domain">
                                                    np:<br/><strong>domain:</strong>buty.pl; www.buty.pl/japonki;<br/>buty.pl; www.buty.pl/klapki;
                                                </span>
                                            </div>    
                                        </div>
                                        <div class="form-group row well">
                                            <div>
                                                <label class="control-label " for="czy_usunac_duplikaty">
                                                    Czy usunąc duplkaty?
                                                </label>
                                                <select class="select form-control" id="czy_usunac_duplikaty" name="czy_usunac_duplikaty">
                                                    <option value="TRUE">Tak</option>
                                                    <option value="FALSE">Nie</option>
                                                </select>
                                                <span class="help-block" id="hint_czy_wypisac_domain">
                                                    np:<br/>buty.pl; www.buty.pl/japonki;<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;; www.buty.pl/klapki;
                                                </span>
                                            </div>    
                                        </div>
                                    </div><a id="a1" style="display: none;" href="#">Personalizuj</a>
                                </div>
                                 </span>           
                            </div>
                            <div class="form-group ">
                            <button type="submit" class="btn btn-primary">Wyślij</button>  
                            </div>
                    </form>
                </div>
            </div>
        </div>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
    $('.wrapper').find('a[href="#"]').on('click', function (e) {
    e.preventDefault();
    this.expand = !this.expand;
    $(this).text(this.expand?"Zwiń":"Personalizuj");
    $(this).closest('.wrapper').find('.small, .big').toggleClass('small big');
    });
    function showMe (box) {

        var chboxs = document.getElementsByName("domyslny");
        var vis = "none";
        for(var i=0;i<chboxs.length;i++) { 
            if(chboxs[i].checked){
             vis = "block";
                break;
            }
        }
        document.getElementById(box).style.display = vis;


    }
    </script> 
    </body>
<?php
ob_end_flush(); //wyrzuc html
?>
</html>