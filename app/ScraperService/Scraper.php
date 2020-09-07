<?php


namespace App\ScraperService;

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

    public function other()
    {
        $c = new Client();
        $data = $c->request("GET", "https://www.worldometers.info/coronavirus/");

        $k = $data->filter("table  > tbody > tr")->each(
            function ($item) {
                $this->key[] = str_replace(":", " ", $item->text());
            }
        );

        $v = $data->filter(".main_table_countries_today  > span")->each(
            function ($item) {
                $this->value[] = $item->text();
            }
        );
        $detaille = [];
        foreach (array_slice($this->key, 8) as $item => $value) {
            $detaille[$item] = explode(" ", $value);
        }
        $nogmeerDetaills = [];
        foreach ($detaille as $item => $value) {
            if (is_string($value[1])) {
                $nogmeerDetaills[$value[1]] = [
                    "totalCases" => $value[2],
                    "newCases" => $value[3],
                    "death" => $value[5] ?? ''
                ];
            }
        }
        print_r($nogmeerDetaills);
//        $ar = array_combine($this->key, $this->value);
//        return ["overview" => $ar];
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

    public function createJsonFile()
    {
        $file = date('Y-m-d') . '_refactoring_file.json';
        $destinationPath = public_path() . "/ScraperStorage/json/";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        File::put($destinationPath . $file, $this->data());
        echo "done";
        return;
    }
}
