<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;
use Illuminate\Support\Facades\Log;

class ScrapingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'writelog:amazon-scraping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'write info messages in log file';

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
    {   // 文字コードを設定する。
        // 日本語だと文字コードの自動解析がうまく動かないようなので、
        // ページに合わせて設定する必要があります
        Log::setDefaultDriver('batch');
        Log::info('start');
        $options = new Options();
        $options->setEnforceEncoding('utf8');

        // 文字化けする場合は Shift JIS を試してみてください
        // $options->setEnforceEncoding('sjis');

        // ページを解析
        $url = 'https://www.amazon.co.jp/gp/product/B07QNJDLGR';
        $dom = new Dom();
        $dom->loadFromUrl($url, $options);

        // 商品名を取得
        $element = $dom->find('#productTitle');
        echo $element->text . "\n";
        Log::info($element->text);

        return Command::SUCCESS;
    }
}
