<?php

Class Cryptography{
    
    function Cryptography(){}
    
    public function Encrypting($pass=""){
        $size = strlen($pass);
            $i = 0;
            $ascii = "";
            while($i<$size){
                //echo ord($pass[$i]).': ';
                $ascii[$i] = ((ord($pass[$i])+3)%95);
                if(($ascii[$i]%(10))==($ascii[$i])){
                    $ascii[$i] = '0'.(string)($ascii[$i]);
                }
                //echo $ascii[$i].'<br>'; //
                $i++;
            }
            
            $hlpr = implode('',$ascii);
            return $hlpr;
    }
    
    public function Unencrypting($pass=""){
        $i=0; $j=0; $arr=""; $size = strlen($pass);
        while($i<$size){
            $arr[$j] = $pass[$i].$pass[$i+1];
            $i=$i+2;
            $j++;
        }
        //echo '<br>';
        $i=0; $ascii = "";
        //echo '<br>'.var_dump($arr).'<br>';
        while($i<$j){
            $ascii[$i] = ((int)($arr[$i])+92);
            if($ascii[$i]>126){
                $ascii[$i] = $ascii[$i]%95;
            }
                $ascii[$i] = chr($ascii[$i]);
                //echo $arr[$i].': '.$ascii[$i].'<br>';
                $i++;
        }
        return (implode('', $ascii));
    }
}

