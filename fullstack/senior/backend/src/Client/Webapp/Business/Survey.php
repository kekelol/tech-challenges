<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 10/02/2018
 * Time: 11:51
 */

namespace IWD\Business;

class Survey {
    private $name; // Name of the agency
    private $code; // Code of the agency
    private $questions; // Questions / Answers for this survey

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function getQuestions() {
        return $this->questions;
    }

    public function setQuestions($questions) {
        $this->questions = $questions;
    }
}