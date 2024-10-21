<?php

class ProductGetController
{
    public static function Index(): void
    {
        $generator = new MetaTagsGenerator();
        $metaTags = $generator->generate([
            "title" => "Подаръци за Всеки Повод",
            "description" => "Открийте нашите специални оферти и промоции за подаръци и материали за тяхното опаковане.",
            "keywords" => "подаръци, промоции, материали за опаковане, празнични подаръци, идеи за подаръци",
            "og:image" => "https://example.com/gift-special-offers.jpg",
            "og:url" => "https://example.com/gift-special-offers"
        ]);
        Setup::View("products/index", ["metaTags" => $metaTags]);
    }
}