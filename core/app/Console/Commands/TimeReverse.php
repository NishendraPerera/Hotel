<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class TimeReverse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'time:reverse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $array = [];
        $tables = DB::select('SHOW TABLES');

        $count = 1;
        foreach($tables AS $table){
            
            $table = $table->Tables_in_spectracube_hotel;
            $columns = DB::select("SHOW COLUMNS FROM ". $table);
            $time_stamps = ['id'];

            if($table=='logs'){
                foreach($columns AS $column){ 
                    if($column->Type == 'timestamp'){
                        $time_stamps[] = $column->Field;
                    }
                }
    
                if(count($time_stamps)>1){
                    $items = DB::table($table)->select($time_stamps)->get();
    
                    $no = 1;
                    if(count($items)>0){
                        foreach($items AS $item){
                            foreach($time_stamps AS $key => $column){
        
                                if($key>0){
                                    if(!is_null($item->{$column})){
                                        $new = date('Y-m-d H:i:s', strtotime('-5 hours -30 minutes', strtotime($item->{$column})));
                                        DB::table($table)->where('id', $item->id)->update([$column => $new ]);
                                    }
                                }
                            }

                            echo $count.'/'.count($tables).' ['.number_format((($no/count($items))*100),2,'.',',').'%] '.$table.'-'.$item->id.PHP_EOL;
                            $no++;
                        }
                    }
                }
            }

            // echo $count.'/'.count($tables).". ".$table.PHP_EOL;
            $count++;
        }
    }
}
