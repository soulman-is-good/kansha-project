<?php

/**
 * Class RssParser
 * @author Eugeniy Mineyev <evgeniy.mineyev@gmail.com>
 */
class RssParser extends X3_Component implements Iterator {

    protected $result;
    protected static $instance;
    protected $url;
    protected $rss;
    protected $position;

    public function __construct($url) {
        $this->url = $url;
    }

    public static function getInstance($url) {
        if (!self::$instance) {
            self::$instance = new self($url);
        }
        return self::$instance;
    }

    public function call() {
        $rss = simplexml_load_file($this->url);

        if (!$rss instanceof SimpleXMLElement) {
            return false;
        }
        return $rss;

        /*$results = array();
        foreach ($rss->channel->item as $item) {
            $result = array();
            $title = (string) $item->title;
            $description = (string) $item->description;
            $link = (string) $item->link;
            $date = time();
            if (property_exists($item, 'pubDate'))
                $date = strtotime($item->pubDate);

            $result['title'] = $title;
            $result['link'] = $link;
            $result['time'] = $date;
            $result['description'] = $description;
            if (is_callable($template)) {
                @call_user_func_array($template, array($result));
            }else
                $results[] = $result;
        }*/
        return $results;
    }

    public function current() {
        
    }

    public function key() {
        
    }

    public function next() {
        
    }

    public function rewind() {
        
    }

    public function valid() {
        
    }

}