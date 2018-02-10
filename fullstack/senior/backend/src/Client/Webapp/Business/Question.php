<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 10/02/2018
 * Time: 12:08
 */

namespace IWD\Business;

class Question {
    private $type; // Type of question (qcm, numeric ...) - String
    private $label; // The question label - String
    private $options; // The possible answers (when type = qcm) - Array of Strings
    private $answer; // The answer (type depending on type of question - qcm=Array of boolean - numeric=Integer)

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getLabel() {
        return $this->label;
    }

    public function setLabel($label) {
        $this->label = $label;
    }

    public function getOptions() {
        return $this->options;
    }

    public function setOptions($options) {
        $this->options = $options;
    }

    public function getAnswer() {
        return $this->answer;
    }

    public function setAnswer($answer) {
        $this->answer = $answer;
    }
}