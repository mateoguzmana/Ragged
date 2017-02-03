<?php

Class Cryptography {

    function Cryptography() {}

    public function Encrypting($pass = "") {
        $i = 0;
        $ascii = "";
        $size = strlen($pass);
        while ($i < $size) {
            $ascii[$i] = ((ord($pass[$i]) + 3) % 95);
            if (($ascii[$i] % (10)) == ($ascii[$i])) {
                $ascii[$i] = '0' . (string) ($ascii[$i]);
            }
            $i++;
        }
        return implode('', $ascii);
    }

    public function Unencrypting($pass = "") {
        $i = 0;
        $j = 0;
        $arr = "";
        $size = strlen($pass);
        while ($i < $size) {
            $arr[$j] = $pass[$i] . $pass[$i + 1];
            $i = $i + 2;
            $j++;
        }
        $i = 0;
        $ascii = "";
        while ($i < $j) {
            $ascii[$i] = ((int) ($arr[$i]) + 92);
            if ($ascii[$i] > 126) {
                $ascii[$i] = $ascii[$i] % 95;
            }
            $ascii[$i] = chr($ascii[$i]);
            $i++;
        }
        return (implode('', $ascii));
    }

}
