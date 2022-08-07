<?php

namespace App\Support;


class Messages {

    private $style;
    private $message;
    
    public function __construct()
    {
        $this->style = '';
    }


    /**
     * You can use any Boostarp text color
     *
     * @param  string $style
     * @return object
     */
    public function style(string $style)
    {
       $this->style .= $style;
       return $this;
    }

    public function errors(string $message)
    {
        $this->message = $message;
        return $this;
    }
    
    public function getMessage(): string
    {
        return $this->html().
        '<div role="alert" class="alert alert-'.$this->style.' alert-dismissible text-monospace sticky-top text-center" style="margin-bottom: 0px;">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <span class="text-black"><strong>Alert: </strong>'.$this->message.'</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>'.$this->endHtml();
    }

    private function html(): string
    {
        return '<!DOCTYPE html>
        <html lang="cs">
        <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <title>SA | </title>
        <meta property="og:type" content="website">
        <meta name="description" content="Adventure|Sci-fi|Fantasy story where the protagonist discovers that he lives in a much more mysterious and amazing world">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/cosmo/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" >
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;700&amp;display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Aldrich">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        </head>
        <body>';
    }

    private function endHtml(): string
    {
        return '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>';
    }
}