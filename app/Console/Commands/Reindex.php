<?php

namespace App\Console\Commands;

use App\Service\Parser\MorrowindUespParserService;
use App\Service\Parser\OblivionUespParserService;
use App\Service\Parser\SkyrimUespParserService;
use Exception;
use Illuminate\Console\Command;
use LogicException;

class Reindex extends Command
{
    private const CURRENT_FILE = '/export-files/%s/%s.json';
    private const INPUT_FILE = '/export-files/%s/%s_input.json';
    private const OUTPUT_FILE = '/export-files/%s/%s_output.json';

    public function __construct(
        private MorrowindUespParserService $morrowindUespParserService,
        private OblivionUespParserService $oblivionUespParserService,
        private SkyrimUespParserService $skyrimUespParserService,
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reindex {game=skyrim}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add `ingredients` field to effects; game param possible values:
    "skyrim", "oblivion", "morrowind"';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $gameName = $this->argument('game');

        if (!$gameName) {
            $this->error('Something went wrong!');

            throw new LogicException('No game argument supplied');
        }
        $this->reindex($gameName);
        $this->info('Done.');

        return 0;
    }

    private function reindex(string $gameName): void
    {
        $inputFilePath = sprintf(self::INPUT_FILE, $gameName, $gameName);
        $this->createInputFileFromCurrentIfNotExists($inputFilePath, $gameName);
        $inputArray = json_decode($this->getRawData(public_path() . $inputFilePath), true);
        $this->info('Getting input from ' . $inputFilePath);

        $dataArray = $this->reindexArray($inputArray);

        $outputFilePath = sprintf(self::OUTPUT_FILE, $gameName, $gameName);
        $this->outputArrayToJsonFile($dataArray, public_path() . $outputFilePath);
        $this->info('Output file ' . $outputFilePath . ' updated.');
    }

    private function getRawData(string $url): string
    {
        $result = file_get_contents($url);
        if ($result === false) {
            throw new Exception('Error when reading file '. $url .' Does the file exist?');
        }
        return $result;
    }

    private function outputArrayToJsonFile(array $dataArray, string $filename): void
    {
        $json = json_encode($dataArray);
        file_put_contents($filename, $json);
    }

    private function createInputFileFromCurrentIfNotExists(string $inputFilePath, string $gameName)
    {
        if (!file_exists(public_path().$inputFilePath)) {
            $currentJsonString = $this->getRawData(
                public_path().         sprintf(self::CURRENT_FILE, $gameName, $gameName)
            );
            file_put_contents(public_path().$inputFilePath, $currentJsonString);
        }
    }

    private function reindexArray(mixed $inputArray): array
    {
        $reindexed = $inputArray;
        $ingredients = $inputArray['ingredients'];
        foreach ($ingredients as $ingredient) {
            $effectNames = $ingredient['effects'];
            foreach ($effectNames as $position => $effectName) {
                $effectArr = $reindexed['effects'][$effectName];

                if (array_key_exists('ingredients', $effectArr)) {
                    $ingredientsInThisPosition = $effectArr['ingredients'][$position];

                    if (!in_array($ingredient['name'], $ingredientsInThisPosition)) {
                        $reindexed['effects'][$effectName]['ingredients'][$position][] = $ingredient['name'];
                    }
                }
            }
        }
        return $reindexed;
    }
}
