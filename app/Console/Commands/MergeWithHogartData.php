<?php

namespace App\Console\Commands;

use App\Service\Parser\MorrowindUespParserService;
use Illuminate\Console\Command;

/**
 * hogart has everything except harvest probability, id, weight, value
 * code is suboptimal, but it's ok. designed to be run once to get json data.
 */
class MergeWithHogartData extends Command
{
    private const HOGART_FILE = '/export-files/morrowind/morrowind_hogart.json';
    private const INPUT_FILE = '/export-files/morrowind/morrowind_input.json';
    private const OUTPUT_FILE = '/export-files/morrowind/morrowind_output.json';

    public function __construct(
        private MorrowindUespParserService $uespParserService
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'morrowind:merge-hogart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'marge current data with hogart data for morrowind';

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
        $currentData = $this->getCurrentData();

        $hogartData = $this->getHogartData();

        $dataArray = $this->mixInHogartData($currentData, $hogartData);
        $this->outputArrayToJsonFile($dataArray, public_path() . self::OUTPUT_FILE);
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

    private function mixInHogartData(array $currentData, array $hogartData): array
    {
        $mergedData = [];
        foreach ($hogartData['ingredients'] as $hogartDatum) {
            $callback = static fn (array $ingredientData) => $ingredientData['name'] === $hogartDatum['name'];
            $ingredientsWithThisName = array_filter($currentData['ingredients'], $callback);
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

    private function getCurrentData(): array
    {
        $json = file_get_contents(public_path() . self::INPUT_FILE);

        return json_decode($json, true);
    }
}
