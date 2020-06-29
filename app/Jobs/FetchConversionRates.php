<?php

namespace App\Jobs;

use App\Repositories\ConversionRateRepository;
use App\Repositories\CurrencyRepository;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\DomCrawler\Crawler;

class FetchConversionRates implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $currencyRepository;
    private $conversionRateRepository;

    public function __construct()
    {
        //
    }

    public function handle(
        CurrencyRepository $currencyRepository,
        ConversionRateRepository $conversionRateRepository
    ): void {
        $this->currencyRepository = $currencyRepository;
        $this->conversionRateRepository = $conversionRateRepository;

        $client = new Client();

        foreach ($this->currencyRepository->getAll() as $baseCurrency) {
            if (!$baseCurrency->iso) {
                continue;
            }

            foreach ($this->currencyRepository->getAll() as $targetCurrency) {
                if (!$targetCurrency->iso || $baseCurrency->iso === $targetCurrency->iso) {
                    continue;
                }

                $url = 'https://www.valutafx.com/' . $baseCurrency->iso . '-' . $targetCurrency->iso . '.htm';

                try {
                    $response = $client->request('GET', $url);
                } catch (Exception $e) {
                    continue;
                }

                $crawler = new Crawler($response->getBody()->__toString());

                $result = $crawler->filter('.converter-result > .rate-value')->first()->text();
                $rate = str_replace(',', '', $result); // Get rid of any potential thousands separators

                $this->conversionRateRepository->createOrUpdate(
                    $baseCurrency->id,
                    $targetCurrency->id,
                    $rate
                );
            }
        }
    }
}
