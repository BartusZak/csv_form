<?php
function wypisz_wszystkie_wiersze_normalnie($item,$kolumny){
    for($i=0;$i<count($item);$i++){
        for ($j=0;$j<=($kolumny-1);$j++){
            if (!isset ($item[$i][$j])){
                $item[$i][$j] = null;
            }
            echo $item[$i][$j].";";
        }
        echo "\n";
    }
}


function domyslny ($ilosc_powt_pierw_kolumny,$ile_wierszy_wypisac,$czy_wypisac_domain = "FALSE",$kolumny,$tab,$czy_usunac = "FALSE"){
    foreach ($tab as $item){
        if (count($item) >= $ilosc_powt_pierw_kolumny ){
            for($i=0;$i<$ile_wierszy_wypisac;$i++){
                for ($j=0;$j<=($kolumny-1);$j++){
                    if (!isset ($item[$i][$j])){
                        $item[$i][$j] = null;
                    }else {
                        if ($czy_usunac == "TRUE"){
                            echo "Usuwam poet 1szej kolumny!";
                            if($i == 0 && $j == 0 && $czy_wypisac_domain == "TRUE"){
                                echo "domain:".$item[$i][$j].";";                             
                            }else {
                                if ($i == 0 && $j == 0 && $czy_wypisac_domain == "FALSE"){
                                    echo $item[$i][$j].";";
                                }
                                elseif($i > 0 && $j == 0 && $czy_wypisac_domain == "FALSE"){
                                    echo ";";
                                }
                                elseif ($j == 1 && $czy_wypisac_domain == "TRUE"){
                                    echo $item[$i][$j].";";
                                }else{
                                    if($j==0){
                                        echo ";";
                                    }
                                    else{
                                        echo $item[$i][$j].";";  
                                    }
                                }
                            }
                        }elseif($czy_usunac == "FALSE"){
                            if($i == 0 && $j == 0 && $czy_wypisac_domain == "TRUE"){
                                echo "domain:".$item[$i][$j].";";
                            }else {
                                echo $item[$i][$j].";";
                            }
                        }
                    }               
                }
                echo "\n";                 
            }
        }elseif (count($item) <= $ilosc_powt_pierw_kolumny){
            wypisz_wszystkie_wiersze_normalnie($item,$kolumny);
        }
    }
    
}
?>