<?php

/*
 *  Mapping to autoload classes: namespace => realpath
 *
 *  Сопоставление для автозагрузки классов: namespace => realpath
 */

namespace App\Optional;

use Hleb\Scheme\Home\Main\Connector;

class MainConnector implements Connector
{
    public function add()
    {
        return [
            "App\Controllers\*"             => "app/Controllers/",
            "Models\*"                      => "app/Models/",
            "App\Middleware\Before\*"       => "app/Middleware/Before/",
            "App\Middleware\After\*"        => "app/Middleware/After/",
            "Modules\*"                     => "modules/",
            "App\Commands\*"                => "app/Commands/",

            // ... or, if a specific class is added,
            // "Phphleb\Debugpan\DPanel"      => "app/ThirdParty/phphleb/debugpan/DPanel.php",

            "DB"                            => "app/Libraries/DB.php",
            "Config"                        => "app/Libraries/Config.php",
            "Translate"                     => "app/Libraries/Translate.php",

            "Sass"                          => "app/Libraries/Sass.php",
            "Content"                       => "app/Libraries/Content.php",
            "UploadImage"                   => "app/Libraries/UploadImage.php",
            "Integration"                   => "app/Libraries/Integration.php",
            "Validation"                    => "app/Libraries/Validation.php",
            "SendEmail"                     => "app/Libraries/SendEmail.php",
            "Tpl"                           => "app/Libraries/Tpl.php",
            "Html"                          => "app/Libraries/Html.php",
            "Meta"                          => "app/Libraries/Meta.php",
            "UserData"                      => "app/Libraries/UserData.php",
            "Forms"                         => "app/Libraries/Forms.php",

            "Slug"                          => "app/ThirdParty/Slug/Slug.php",
            "Parsedown"                     => "app/ThirdParty/Parsedown/Parsedown.php",
            "MyParsedown"                   => "app/ThirdParty/Parsedown/MyParsedown.php",
            "SimpleImage"                   => "app/ThirdParty/SimpleImage.php",
            "URLScraper"                    => "app/ThirdParty/URLScraper.php",
            "JacksonJeans\MailException"    => "app/ThirdParty/php-mail/MailException.class.php",
            "JacksonJeans\Mail"             => "app/ThirdParty/php-mail/Mail.class.php",
            "Domain"                        => "app/ThirdParty/Domain/Domain.php",
        ];
    }
}
