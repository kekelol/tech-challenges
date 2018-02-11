<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 10/02/2018
 * Time: 13:45
 */

namespace IWD\Logic;

class SurveyLogic {
    private $jsonDataPath;
    private $allSurveys;
    private $filteredSurveys;
    /**
     * SurveyLogic constructor.
     * @param string Path to JSON files directory
     */
    public function __construct($jsonDataPath) {
        $this->jsonDataPath = $jsonDataPath;
    }

    /**
     * Loops threw all Json files, retrieves its data, and instantiates an Array of objects (Surveys & Questions)
     * @return array of Surveys
     */
    function retrieveJsonData() {
        $this->allSurveys = [];
        if ($folder = opendir(ROOT_PATH.'\data')) {
            // Loop threw all JSON (survey) files and create Array of Surveys
            while (false !== ($file = readdir($folder))) {
                if ($file != '.' && $file != '..') {
                    $str = file_get_contents($this->jsonDataPath.'\\'.$file);
                    $json = json_decode($str, true); // Decodes the JSON into an associative array
                    $survey = new \IWD\Business\Survey();
                    $survey->setCode($json['survey']['code']);
                    $survey->setName($json['survey']['name']);
                    $questions = [];
                    foreach ($json['questions'] as $field => $value) {
                        $question = new \IWD\Business\Question();
                        $question->setType($value['type']);
                        $question->setLabel($value['label']);
                        // As of now, only type of supported answers are QCM (array of answers) and Numeric
                        switch ($question->getType()) {
                            case 'qcm':
                                if (count($value['options']) > 0) {
                                    $options = [];
                                    foreach ($value['options'] as $optionValue) {
                                        array_push($options, $optionValue);
                                    }
                                    $question->setOptions($options);
                                }
                                if (count($value['answer']) > 0) {
                                    $options = [];
                                    foreach ($value['answer'] as $optionValue) {
                                        array_push($options, $optionValue);
                                    }
                                    $question->setAnswer($options);
                                }
                                break;
                            case 'numeric':
                                $question->setAnswer($value['answer']);
                                break;
                            default:
                                break;
                        }
                        array_push($questions, $question);
                        $survey->setQuestions($questions);
                    }
                    array_push($this->allSurveys, $survey);
                }
            }
            closedir($folder);
        }
        return $this->allSurveys;
    }

    /**
     * @param string Agency code (string)
     * @return array of Surveys which correspond to this Agency code
     */
    private function getSurveysFromCode($code) {
        $filteredSurveys= [];
        foreach ($this->allSurveys as $currentSurvey) {
            if ($currentSurvey->getCode() == $code) {
                array_push($filteredSurveys, $currentSurvey);
            }
        }
        return $filteredSurveys;
    }

    /**
     * * Aggregates results from all surveys of an agency in a generic way so that it works regardless of the number of questions,
     * * the type of questions, the type of possible answers in qcm questions
     * @param string the code of an agency
     * @return array of aggregated answers from all surveys of this agency (JSON) which aggregates results from questions of all Surveys from this agency
     */
    function getAggregatedResultsByAgency($code) {
        $filteredSurveys = $this->getSurveysFromCode($code);
        $this->filteredSurveys = $filteredSurveys;
        $aggregatedAnswers = [];
        if (count($filteredSurveys) > 0) {
            foreach ($filteredSurveys as $currentSurvey) {
                $questions = $currentSurvey->getQuestions();
                foreach ($questions as $currentQuestion) {
                    $label = $currentQuestion->getlabel();
                    // If it's the first time we encounter this kind of question
                    if (!array_key_exists($label, $aggregatedAnswers)) {
                        $aggregatedAnswers[$label]['type'] = $currentQuestion->getType();
                        $aggregatedAnswers[$label]['label'] = $label;
                        switch ($currentQuestion->getType()) {
                            case 'qcm':
                                {
                                    $aggregatedAnswers[$label]['options'] = $currentQuestion->getOptions();
                                    $answers = $currentQuestion->getAnswer();
                                    for ($i = 0; $i < count($answers); $i++) {
                                        $oneLabel = $aggregatedAnswers[$label]['options'][$i];
                                        $oneAnswer = $answers[$i];
                                        if ($oneAnswer) {
                                            $aggregatedAnswers[$label]['answers'][$oneLabel] = 1;
                                        } else {
                                            $aggregatedAnswers[$label]['answers'][$oneLabel] = 0;
                                        }
                                    }
                                    break;
                                }
                            // If numerical question, we calculate an average of the answers to that question
                            case 'numeric':
                                {
                                    $aggregatedAnswers[$label]['sumNumericQuestions'] = intval($currentQuestion->getAnswer());
                                    $aggregatedAnswers[$label]['nbNumericQuestions'] = 1;
                                    $aggregatedAnswers[$label]['avgNumericQuestions'] = intval($currentQuestion->getAnswer());
                                    break;
                                }
                            default:
                                break;
                        }
                    } else {
                        switch ($currentQuestion->getType()) {
                            case 'qcm':
                                {
                                    $aggregatedAnswers[$label]['options'] = $currentQuestion->getOptions();
                                    $answers = $currentQuestion->getAnswer();
                                    for ($i = 0; $i < count($answers); $i++) {
                                        $oneLabel = $aggregatedAnswers[$label]['options'][$i];
                                        $oneAnswer = $answers[$i];
                                        if ($oneAnswer) {
                                            $aggregatedAnswers[$label]['answers'][$oneLabel]++;
                                        }
                                    }
                                    break;
                                }
                            // If numerical question, we calculate an average of the answers to that question
                            case 'numeric':
                                {
                                    $aggregatedAnswers[$label]['sumNumericQuestions'] += intval($currentQuestion->getAnswer());
                                    $aggregatedAnswers[$label]['nbNumericQuestions']++;
                                    $aggregatedAnswers[$label]['avgNumericQuestions'] = round($aggregatedAnswers[$label]['sumNumericQuestions'] / $aggregatedAnswers[$label]['nbNumericQuestions']);
                                    break;
                                }
                            default:
                                break;
                        }
                    }
                }
            }
        }
        return $aggregatedAnswers;
    }

    function getJsonAggregatedResults($aggregatedAnswers) {
        // Construction du JSON
        $jsonString = "{";
        if (count($aggregatedAnswers) > 0) {
            $jsonString .= '"agency": { "name": "' . $this->filteredSurveys[0]->getName() . '", "code": "' . $this->filteredSurveys[0]->getCode() . '" },';
            $jsonString .= '"questions": [';
            foreach ($aggregatedAnswers as $answer) {
                switch ($answer['type']) {
                    case 'qcm':
                        {
                            $jsonString .= '{"type": "' . $answer['type'] . '", "label": "' . $answer['label'] . '", "answers": [';
                            foreach ($answer['answers'] as $oneQcmOption => $oneQcmAnswer) {
                                $jsonString .= '{"' . $oneQcmOption . '": ' . $oneQcmAnswer . '},';
                            }
                            $jsonString = substr($jsonString, 0, strlen($jsonString)-1);
                            $jsonString .= ']}';
                            break;
                        }
                    // If numerical question, we calculate an average of the answers to that question
                    case 'numeric':
                        {
                            $jsonString .= '{"type": "' . $answer['type'] . '", "label": "' . $answer['label'] . '", "sum": ' . $answer['sumNumericQuestions'] . ', "count": ' . $answer['nbNumericQuestions'] . ', "average": ' . $answer['avgNumericQuestions'] . '}';
                            break;
                        }
                    default:
                        $jsonString .= '{}';
                        break;
                }

                if (next($aggregatedAnswers) == true) $jsonString .= ",";
            }
            $jsonString .= ']';
        }
        $jsonString .= "}";
        return $jsonString;
    }
}