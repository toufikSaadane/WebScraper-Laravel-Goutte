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

    public function scrapingMorocco(){

        $c = new Client();
        $data = $c->request("GET", "https://www.worldometers.info/coronavirus/country/morocco/");

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
        return ["morocco" => $ar];
    }

    public function scrapingNederland(){

        $c = new Client();
        $data = $c->request("GET", "https://www.worldometers.info/coronavirus/country/netherlands/");

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
        return ["nederland" => $ar];
    }
    public function data(){
        return collect([
            $this->totalOverview(),
            $this->scrapingMorocco(),
            $this->scrapingNederland()
        ])->toJson();
    }
    public function createJsonFile(){
        $file = date('Y-m-d'). '_file.json';
        $destinationPath=public_path()."/ScraperStorage/json/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$this->data());
        echo "done";
        return;
    }
}
