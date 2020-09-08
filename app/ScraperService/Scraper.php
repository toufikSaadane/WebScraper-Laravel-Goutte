<?php


namespace App\ScraperService;

use App\Console\Commands\dailyReport;
use Goutte\Client;
use Illuminate\Support\Facades\File;

class Scraper
{
    private $key = [];
    private $value = [];


    /**
     * @return array
     */
    public function totalOverview()
    {
        $c = new Client();
        $data = $c->request("GET", "https://www.worldometers.info/coronavirus/");

        $k = $data->filter("#maincounter-wrap  > h1")->each(
            function ($item) {

                $this->key[] = str_replace(":", " ", $item->text());

            }
        );

        $v = $data->filter(".maincounter-number  > span")->each(
            function ($item) {
                $this->value[] = $item->text();
            }
        );

        $ar = array_combine($this->key, $this->value);
        return ["overview" => $ar];
    }

    public function scrapingMorocco()
    {
        return ["morocco" => $this->mainOperations('morocco')];
    }

    public function scrapingNederland()
    {
        return ["nederland" => $this->mainOperations('netherland')];
    }


    private function mainOperations(string $land)
    {
        $c = new Client();
        $data = $c->request("GET", "https://www.worldometers.info/coronavirus/country/$land/");

        $k = $data->filter("#maincounter-wrap  > h1")->each(
            function ($item) {

                $this->key[] = str_replace(":", " ", $item->text());

            }
        );

        $v = $data->filter(".maincounter-number  > span")->each(
            function ($item) {
                $this->value[] = $item->text();
            }
        );

        return array_combine($this->key, $this->value);
    }



    private function data()
    {
        return collect([
            $this->totalOverview(),
            "countries" => [
                $this->scrapingMorocco(),
                $this->scrapingNederland()
            ]
        ])->toJson();
    }

    public function createJsonFile($command)
    {
        $file = date('Y-m-d') . '_refactoring_file.json';
        $destinationPath = public_path() . "/ScraperStorage/json/";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        if(in_array(["overview", "dailyReport"])){

            if ($command == 'overview'){
                File::put($destinationPath . $file, $this->other());

            }
            elseif($command == 'dailyReport') {
                File::put($destinationPath . $file, (new DailyReposrt())->renderData());
            }
        }else{
            echo "choose one the following arguments:\n -overview\n  dailyReport\n";
        }


        echo "done\n";
        return;
    }
}
