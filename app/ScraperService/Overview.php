<?php


namespace App\ScraperService;

use Goutte\Client;
use Illuminate\Support\Facades\File;

class Overview
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

       $data->filter("#maincounter-wrap  > h1")->each(
            function ($item) {

                $this->key[] = str_replace(":", " ", $item->text());

            }
        );

        $data->filter(".maincounter-number  > span")->each(
            function ($item) {
                $this->value[] = $item->text();
            }
        );

        return array_combine($this->key, $this->value);
    }


    public function data()
    {
        return collect([
            $this->totalOverview(),
            $this->scrapingMorocco(),
            $this->scrapingNederland()
        ])->toJson();
    }

}
