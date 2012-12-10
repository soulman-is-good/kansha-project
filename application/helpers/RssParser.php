<?php

/**
 * Class RssParser
 * @author Eugeniy Mineyev <evgeniy.mineyev@gmail.com>
 */
class RssParser extends X3_Component
{
    protected $result;
    protected static $instance;

    public function  __construct() {}

    public static function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function call($url, $template=false)
    {
        if(!is_string($url)) {
            throw new Exception('Url должен быть строкой');
        }

        $rss = simplexml_load_file($url);

        if(!$rss instanceof SimpleXMLElement) {
            return false;
        }

        $results = array();
        foreach($rss->channel->item as $item) {
            $result = array();
            $title = (string) $item->title;
            $description = (string) $item->description;
            $link = (string) $item->link;
            $date = time();
            if(property_exists($item, 'pubDate'))
                $date = strtotime($item->pubDate);

            $result['title'] = $title;
            $result['link'] = $link;
            $result['time'] = $date;
            $result['description'] = $description;
            if(is_callable($template)){
                @call_user_func_array($template, array($result));
            }else
                $results[] = $result;
        }
        return $results;
    }
}