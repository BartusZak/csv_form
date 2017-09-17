<?php
function wypisz_wszystkie_wiersze_normalnie($item,$kolumny,$filename){
    for($i=0;$i<count($item);$i++){
        for ($j=0;$j<=($kolumny-1);$j++){
            if (!isset ($item[$i][$j])){
                $item[$i][$j] = null;
            }
            echo $item[$i][$j].";";
        }
        echo "\n";
    }
    header('Content-Disposition: attachment;filename="'.$filename.'";');
}


function domyslny ($ilosc_powt_pierw_kolumny,$ile_wierszy_wypisac,$czy_wypisac_domain = TRUE,$kolumny,$tab,$czy_usunac_powt_pierw_kolumny = TRUE,$filename){
    foreach ($tab as $item){
        if (count($item) >= $ilosc_powt_pierw_kolumny ){
            for($i=0;$i<$ile_wierszy_wypisac;$i++){
                for ($j=0;$j<=($kolumny-1);$j++){
                    if (!isset ($item[$i][$j])){
                        $item[$i][$j] = null;
                    }else {
                        if ($czy_usunac_powt_pierw_kolumny){
                            if($i == 0 && $j == 0 && $czy_wypisac_domain){
                                echo "domain:".$item[$i][$j].";";                             
                            }else {
                                if ($i == 0 && $j == 0 && $czy_wypisac_domain == FALSE){
                                    echo $item[$i][$j].";";
                                }
                                elseif($i > 0 && $j == 0 && $czy_wypisac_domain == FALSE){
                                    echo ";";
                                }
                                elseif ($j == 1 && $czy_wypisac_domain){
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
                        }else{
                            if($i == 0 && $j == 0 && $czy_wypisac_domain){
                                echo "domain:".$item[$i][$j].";";
                            }else {
                                echo $item[$i][$j].";";
                            }
                        }
                    }               
                }
                echo "\n";                 
            }
            header('Content-Disposition: attachment;filename="'.$filename.'";');
        }elseif (count($item) <= $ilosc_powt_pierw_kolumny){
            wypisz_wszystkie_wiersze_normalnie($item,$kolumny,$filename);
        }
    }
    
}
?>