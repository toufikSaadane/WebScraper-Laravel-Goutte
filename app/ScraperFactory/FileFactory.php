<?php


namespace App\ScraperFactory;


use App\ScraperService\DailyReposrt;
use App\ScraperService\Overview;
use Illuminate\Support\Facades\File;

class FileFactory
{
    public function createFile($command)
    {

        if (in_array($command, ["overview", "dailyReport"])) {
            if ($command == 'overview') {
                File::put($this->createFolder() . $this->FilesName($command), (new Overview())->data());
            } elseif ($command == 'dailyReport') {
                File::put($this->createFolder() . $this->FilesName($command), (new DailyReposrt())->renderData());
            }
        } else {
            echo "choose one the following reports type:\n --overview\n --dailyReport\n";
        }
        echo "done\n";
        return;
    }

    public function createFolder(){
        $destinationPath = storage_path() . "/ScraperStorage/json/";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        return $destinationPath;
    }

    public function FilesName($command){
        return date('Y-m-d') ."-".$command.'.json';

    }

}
