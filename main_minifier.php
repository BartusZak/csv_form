<?php
//Fatal error: Allowed memory size of 16777216 bytes exhausted (tried to allocate 256 bytes) i
ini_set('memory_limit', '256M');
//TO DO LIST
// - dodać czytanie ile jest max kolumn
// - dodawanie selektywne po kolumnach

require_once 'functions.php';
//echo "<pre>";
//echo var_dump($_FILES['plik']);
//echo "</pre>";

//max available uploaded file size in MB
$max_file_size = 1;

$kolumny = ((isset($_POST['kolumny']) && !empty($_POST['kolumny']))? $_POST['kolumny'] : 21);
$uploaded_file_name = ((isset($_FILES['plik']['name']) && !empty($_FILES['plik']['name']))? filter_var($_FILES['plik']['name'], FILTER_SANITIZE_STRING) : "plik.csv");
$uploaded_file_ext = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);  

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
                                    domyslny($_POST['ilosc_powt'], $_POST['ile_wierszy'], $_POST['czy_wypisac_domain'], $kolumny, $tab, $_POST['czy_usunac_duplikaty']);                               
                                }else{
                                    echo "<div class='alert alert-danger'><strong>Ilość wystąpień</strong> zawartości nie może być mniejsza od <strong>ilości wierszy</strong> które chcesz wyświetlić!</div>";
                                    break;
                                }
                            }else{
                                domyslny(4, 1, "TRUE", $kolumny, $tab, "FALSE");
                            }
                            

                        }else{
                            foreach ($tab as $item){
                                wypisz_wszystkie_wiersze_normalnie($item, $kolumny);
                            }
                        }
                        header("Content-type: text/csv");
                        header('Content-Disposition: attachment;filename="'.$new_file.'";');
                       exit;

                    }else{
                        echo "<div class='alert alert-danger'>Niedozwolone rozszerzenie piku!</div>";
                        echo "<div class='alert alert-info'>Dozwolone rozszerzenia pliku: <strong>".$mimes_print."</strong>.</div>";
                    }
                }else{
                    echo "Plik który chcesz przesłać musi mieć nazwę!";
                }
                break;
            }
            case 1:{
                echo "<div class='alert alert-danger'>Za duży plik (php.ini)</div>";
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
         echo "<div class='alert alert-info'>Dozwolona wielkość pliku: <strong>".($max_file_size*1024)."</strong> KB .<br>Wielkość przesłanego pliku: <strong>".round((($_FILES['plik']['size'])/1024), 0)."</strong> KB</div>";
     }
     }
?>