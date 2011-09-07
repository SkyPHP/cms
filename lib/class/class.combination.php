<?php
class combination
{
    private $combinations = array();
    
    public function __construct($array_Words=array(), $get_uniques=1)
    {
        for ($i=1; $i<=count($array_Words); $i++)
        {
            $this->bul($array_Words, $get_uniques, $i);
        }
    }
    private function bul($arrayWords=array(), $get_uniques=1, $uzunluk, $onceki="")
    {
        for ($i=0; $i<count($arrayWords); $i++)
        {
            $c = array();
            if ($uzunluk == 1)
            {
                $s = explode(" ", trim($onceki." ".$arrayWords[$i]));
                $isunique = 1;
                if ($get_uniques)
                {
                    $uniques = array();
                    for ($r=0; $r<count($s); $r++)
                    {
                        if (in_array($s[$r], $uniques))
                        {
                            $isunique = 0;
                            break;
                        }
                        else
                        {
                            $uniques[] = $s[$r];
                        }
                    }
                }
                if ($get_uniques)
                {
                    sort($s);
                    if (!in_array($s, $this->combinations) && $isunique)
                    {
                        if ($isunique)
                        {
                            $this->combinations[] = $s;
                        }
                    }
                }
                else
                {
                    $this->combinations[] = $s;
                }
            }
            else
            {
                $this->bul($arrayWords, $get_uniques, $uzunluk - 1, $onceki." ".$arrayWords[$i]);
            }
        }
    }
    public function getCombinations()
    {
        return $this->combinations;
    }
}

?>