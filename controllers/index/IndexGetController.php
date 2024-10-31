<?php

class IndexGetController
{
    public static function Home(): void
    {
        $generator = new MetaTagsGenerator();
        $metaTags = $generator->generate([
            "title" => "Подаръци за Всеки Повод",
            "description" => "Открийте нашите специални оферти и промоции за подаръци и материали за тяхното опаковане.",
            "keywords" => "подаръци, промоции, материали за опаковане, празнични подаръци, идеи за подаръци",
            "og:image" => "https://example.com/gift-special-offers.jpg",
            "og:url" => "https://example.com/gift-special-offers"
        ]);
        Setup::View("index/home", [
            "user" => AuthService::isAuth() ?? null,
            "metaTags" => $metaTags
        ]);
    }
    
    public static function About(): void
    {
        Setup::View("index/about", [
            "user" => AuthService::isAuth() ?? null,
        ]);
    }

    public static function Contacts(): void
    {
        Setup::View("index/contacts", [
            "user" => AuthService::isAuth() ?? null,
        ]);
    }

    public static function Policy(): void
    {
        Setup::View("index/policy", [
            "user" => AuthService::isAuth() ?? null,
        ]);
    }
}
