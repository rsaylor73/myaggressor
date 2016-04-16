<?php

class Resellers {

        public $linkID;

        function __construct($linkID){ $this->linkID = $linkID; }

        public function new_mysql($sql) {

                $result = $this->linkID->query($sql) or die($this->linkID->error.__LINE__);
                return $result;
        }

}
?>
