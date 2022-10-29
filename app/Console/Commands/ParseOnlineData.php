<?php

namespace App\Console\Commands;

use App\Service\Parser\MorrowindUespParserService;
use App\Service\Parser\OblivionUespParserService;
use App\Service\Parser\ParserInterface;
use Exception;
use Illuminate\Console\Command;
use LogicException;

/**
 * @see https://elderscrolls.fandom.com/wiki/Ingredients_(Morrowind)
 * @see https://en.uesp.net/wiki/Morrowind:Ingredients
 * code is suboptimal, but it's ok. designed to be run once to get json data.
 * it works iteratively, changing current version of the file
 */
class ParseOnlineData extends Command
{
    private const SOURCE_FILE_FORMAT = '/app/parse/%s/%s.html';
    private const CURRENT_FILE = '/export-files/%s/%s.json';
    private const INPUT_FILE = '/export-files/%s/%s_input.json';
    private const OUTPUT_FILE = '/export-files/%s/%s_%s_output.json';

    public function __construct(
        private MorrowindUespParserService $morrowindUespParserService,
        private OblivionUespParserService $oblivionUespParserService,

    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse-data {game=skyrim} {source=uesp}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'parse data. receives 2 arguments : game and source. game possible values:
    "skyrim", "oblivion", "morrowind"; source possible values: "uesp", "fandom"';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $gameName = $this->argument('game');
        $source = $this->argument('source');

        if (!$gameName || !$source) {
            $this->error('Something went wrong!');

            throw new LogicException('No game or source argument supplied');
        }
        $this->parseOnlineData($gameName, $source);
        $this->info('Done.');

        return 0;
    }

    private function parseOnlineData(string $gameName, string $source): void
    {
        $filePath = sprintf(self::SOURCE_FILE_FORMAT, $gameName, $source);
        $rawDataString = $this->getRawData(storage_path() . $filePath);
        $this->info('Source file for parsing ' . $filePath);

        $inputFilePath = sprintf(self::INPUT_FILE, $gameName, $gameName);
        $this->createInputFileFromCurrentIfNotExists($inputFilePath, $gameName);
        $inputArray = json_decode($this->getRawData(public_path() . $inputFilePath), true);
        $this->info('Getting input from ' . $inputFilePath);

        $parser = $this->getParser($gameName, $source);
        $dataArray = $parser->parse($rawDataString, $inputArray);

        $outputFilePath = sprintf(self::OUTPUT_FILE, $gameName, $gameName, $source);
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

    private function getParser(string $gameName, string $source): ParserInterface
    {
        $parser = null;
        $parser = match ([$gameName, $source]) {
            ['skyrim', 'uesp'] => null,
            ['skyrim', 'fandom'] => null,
            ['oblivion', 'uesp'] => $this->oblivionUespParserService,
            ['oblivion', 'fandom'] => null,
            ['morrowind', 'uesp'] => $this->morrowindUespParserService,
            ['morrowind', 'fandom'] => null,
            default => null,
        };

        if (!$parser) {
            throw new Exception('Cannot find parser for chosen game and source.');
        }

        return $parser;
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
}
