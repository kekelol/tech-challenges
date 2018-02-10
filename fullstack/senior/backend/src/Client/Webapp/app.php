<?php
declare(strict_types=1);

if (file_exists(ROOT_PATH.'/vendor/autoload.php') === false) {
    echo "run this command first: composer install";
    exit();
}
require_once ROOT_PATH.'/vendor/autoload.php';
require_once ('Business/Question.php');
require_once ('Business/Survey.php');
require_once ('Logic/SurveyLogic.php');

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

$app = new Application();
$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

$app->get('/', function () use ($app) {
    return '{"status":"error", "message":"Please provide parameter (ex: /XX1)"}';
});

$app->get('/{survey_code}', function (Silex\Application $app, $survey_code) use ($app) {
    $SurveyLogic = new \IWD\Logic\SurveyLogic(ROOT_PATH.'\data'); // Defines path to JSON data source
    $SurveyLogic->retrieveJsonData(); // Retrieves data from all JSON files
    $aggregatedResults = $SurveyLogic->getAggregatedResultsByAgency($survey_code); // Aggregates results from all surveys and questions / answers for the agency where code is passed in parameters
    $jsonResults = $SurveyLogic->getJsonAggregatedResults($aggregatedResults); // Formats aggregated results to JSON
    return $jsonResults;
});

$app->run();

return $app;