<?php
    namespace Cerceau\View\Helper;

    class Pagination {

        public function __construct( $url, $totalRowsCount, $currentPageNumber = 1, $numRowsOnPage = 10 ){
            $this->url = $url;
            $this->totalRowsCount = $totalRowsCount;
            $this->currentPageNumber = $currentPageNumber;
            $this->numRowsOnPage = $numRowsOnPage;

        }

        private function getTotalPageCount(){
            if( $this->totalRowsCount > $this->numRowsOnPage ){
                if( $this->totalRowsCount % $this->numRowsOnPage > 0 )
                    $totalPageCount = (integer)( $this->totalRowsCount / $this->numRowsOnPage ) + 1;
                else
                    $totalPageCount = ( $this->totalRowsCount / $this->numRowsOnPage );
            }
            else{
                $totalPageCount = 1;
            }

            return $totalPageCount;
        }


        public function printList(){
            $totalPageCount = $this->getTotalPageCount();

            if( ( $this->currentPageNumber - 1 ) <= 0 )
                $prevPageNumber = 1;
            else
                $prevPageNumber = $this->currentPageNumber - 1;

            if( ( $this->currentPageNumber ) >= $totalPageCount )
                $nextPageNumber = $totalPageCount;
            else
                $nextPageNumber = $this->currentPageNumber + 1;


            if( $this->currentPageNumber == 1 )
                $classPrev = 'class="disabled"';
            else
                $classPrev = '';
            echo <<<HTML
                <li {$classPrev}><a href="/{$this->url}?page={$prevPageNumber}">Prev</a></li>
HTML;

            for( $i = 1; $i < $totalPageCount + 1; $i++ ){
                if( $this->currentPageNumber == $i )
                    $classCurrent = 'class="active"';
                else
                    $classCurrent = '';
                echo <<<HTML
                    <li {$classCurrent}><a href="/{$this->url}?page={$i}">{$i}</a></li>
HTML;
            }

            if( $this->currentPageNumber == $totalPageCount )
                $classNext = 'class="disabled"';
            else
                $classNext = '';
            echo <<<HTML
                <li {$classNext}><a href="/{$this->url}?page={$nextPageNumber}">Next</a></li>
HTML;
        }
    }
