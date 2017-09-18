<?php
//bez tego problem z nagłówkami
ob_start();
require_once 'main_minifier.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>.csv Minifier</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <div class="container-fluid">
            <h1>Minifier .csv</h1>
            <h2>Jak korzystać?</h2>
            <ol>
                <li>Wybierz plik do obróbki.</li>
                <li>Podaj opcjonalnie ile kolumn chcesz wyświetlić oraz nową nazwę pliku.</li>
                <li>Jeśli nie zaznaczysz sposobu minifikacji możesz zmienić jedynie nazwę pliku lub ilość kolumn.</li>
                <li>Zaznaczając <i>"Domyślny"</i> pod opisem pojawi się <i>"Personalizuj"</i>, gdzie masz możliwośc edycji.</li>
                <li>Jeśli nie wpiszesz żadnych wartości w pola <i>"Ilość wystąpień"</i> oraz <i>"Ile wierszy"</i> skrypt wykona domyślną minifikację</li> 
            </ol>
            <div class="row" style="margin:20px;">
            <span class="label label-warning"><strong>Uwaga!</strong> Skrypt porównuje jedynie pierwszą kolumnę w poszukiwaniu duplikatów. Upewnij się, że twój plik .csv lub .txt ma prawidłową konstrukcję!</span>
            </div>
            <div class="row">
                <div class="col-md-4">
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
                                                <img id="img_help1" class="initiallyHidden" src="img/help_img1.jpg"/>
                                                <a id="img_help1">Pokaż pomoc</a>                                                   
                                                
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
                                                <img id="img_help2" class="initiallyHidden" src="img/help_img2.jpg"/>
                                                <a id="img_help2">Pokaż pomoc</a>  
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
    <script src="js/script.js" async></script>
    </body>
<?php
ob_end_flush(); //wyrzuc html
?>
</html>