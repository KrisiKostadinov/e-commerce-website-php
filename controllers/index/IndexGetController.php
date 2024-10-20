<?php

class IndexGetController
{
    public static function Home(): void
    {
        Setup::View("index/home");
    }
    
    public static function Contacts(): void
    {
        Setup::View("index/contacts");
    }
}