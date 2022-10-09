<?php

namespace App\Console\Commands;

use App\Service\Parser\UespParserService;
use Illuminate\Console\Command;

/**
 * @see https://elderscrolls.fandom.com/wiki/Ingredients_(Morrowind)
 * @see https://en.uesp.net/wiki/Morrowind:Ingredients
 * hogart has everything except harvest probability and id
 * code is suboptimal, but it's ok. designed to be run once to get json data.
 */
class ParseOnlineDataMorrowind extends Command
{
    private const HOGART_FILE = '/export-files/morrowind/morrowind_hogart.json';
    private const UESP_RAW_FILE = '/app/parse/uesp.html';
    private const UESP_FILE = '/export-files/morrowind/morrowind_uesp.json';
    private const UESP_URL = 'https://en.uesp.net/wiki/Morrowind:Ingredients';

    public function __construct(
        private UespParserService $uespParserService
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'morrowind:parse-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'parse data for morrowind';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->parseUespDataMorrowind();

        return 0;
    }

    private function parseUespDataMorrowind()
    {
        $rawDataString = $this->getRawData(storage_path() . self::UESP_RAW_FILE);
        $hogartData = $this->getHogartData();

        $dataArray = $this->uespParserService->parse($rawDataString, $hogartData);
        $dataArray = $this->mixInHogartData($dataArray);
        $this->outputArrayToJsonFile($dataArray, public_path() . self::UESP_FILE);
    }

    private function getRawData(string $url): string
    {
        return file_get_contents($url);
    }

    private function outputArrayToJsonFile(array $dataArray, string $filename): void
    {
        $json = json_encode($dataArray);
        file_put_contents($filename, $json);
    }

    private function mixInHogartData(array $dataArray)
    {
        $json = file_get_contents(public_path(). self::HOGART_FILE);
        $hogartData = json_decode($json, true);

        $mergedData = [];
        foreach ($hogartData['ingredients'] as $hogartDatum) {
            $callback = static fn (array $ingredientData) => $ingredientData['name'] === $hogartDatum['name'];
            $ingredientsWithThisName = array_filter($dataArray['ingredients'], $callback);
            if (count($ingredientsWithThisName) > 0) {
                $element = $ingredientsWithThisName[array_key_first($ingredientsWithThisName)];
                $id = $element['id'];
                $mergedData['ingredients'][$id] = [
                    'id' => $id,
                    'harvest_probability' => $element['harvest_probability'],
                    ...$hogartDatum,
                ];
            }
        }

        return $mergedData;
    }

    private function getHogartData(): array
    {
        $json = file_get_contents(public_path() . self::HOGART_FILE);
        return json_decode($json, true);
    }
}
