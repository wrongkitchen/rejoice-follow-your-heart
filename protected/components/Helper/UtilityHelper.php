<?php
    Class UtilityHelper
    {  
        public static function pagination($currentPage,$noOfPage,$noOfPageText)
        {
            $offset1        = ceil($noOfPageText / 2);
            $offset2        = $noOfPageText - $offset1; 
            $pageArray      = array();
            if ($noOfPage <= $noOfPageText){
                for ($i = 1; $i <= $noOfPage; $i++){
                    $pageArray[] = $i;
                }
            }else{
                if (($currentPage - $offset1) < 1){
                    for ($i = 1; $i <= $noOfPageText;$i++){
                        $pageArray[] = $i;
                    }                    
                }elseif ($currentPage >= ($noOfPage - $offset2)){
                    $start = ($noOfPage - ($noOfPageText - 1));
                    $end   = $start + $noOfPageText;
                    for ($i = $start; $i < $end;$i++){
                        $pageArray[] = $i;
                    }                  
                }else{
                    $start  = $currentPage - $offset1 + 1;
                    $end    = $start + $noOfPageText;
                    for ($i = $start; $i < $end;$i++){
                        $pageArray[] = $i;
                    }
                }
            }
            $nextPage   = (($currentPage) != $noOfPage && $noOfPage >= 1)
                        ? $currentPage + 1
                        : '';
            $prevPage   = (($currentPage) != 1)
                        ? $currentPage - 1
                        : '';
            $firstPage  = 1;
            $lastPage   = $noOfPage;
            
            return array( 
                    'pageArray'     =>  $pageArray,
                    'firstPage'     =>  $firstPage,
                    'lastPage'      =>  $lastPage,
                    'nextPage'      =>  $nextPage,
                    'prevPage'      =>  $prevPage,
                    'currentPage'   =>  $currentPage,
                    'noOfPageText'  =>  $noOfPageText
            );
        }
	}
?>