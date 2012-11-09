<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Loader
 *
 * @author Soul_man
 */

class Loader extends X3_Component{
 
    public $loader = null;
    public static $mime_types = array(

        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',

        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );            
    
    public function init() {
        
    }
    
    public static function load($filename,$params,$type=null) {
        $self = new self();
        $class = "Goods" . ucfirst($type) . "Loader";
        if($type == 'xlsx')
            class_alias("GoodsXlsLoader","GoodsXlsxLoader");
        $self->loader = new $class();
        if($self->loader->load($filename,$params))
            return $self->loader;
        return false;
    }
}

interface GoodsUploadInterface {
	public function load($filename, $params);
	public function next();
}

class GoodsCsvLoader implements GoodsUploadInterface {
	
	private $id, $price, $name, $group, $url, $delim, $enc, $desc, $articule;
	private $f, $last, $data;
        private $filename;
	
	public function load($filename, $params) {
		$this->id = $params['id'];
		$this->price = $params['price'];
		$this->articule = $params['articule'];
		$this->url = $params['url'];
		$this->name = $params['name'];
		$this->photo = $params['photo'];
		$this->delim = $params['delimiter'];
		$this->desc = $params['desc'];
		$this->enc = isset($params['encoding'])?$params['encoding']:'windows-1251';
		$this->f = fopen($filename, 'r');
		$this->data = array();
		$this->last = '';
                $this->filename = $filename;
		if (!$this->f) {
			return false;
		}
		return $this;
	}
	
	public function next() {
		if (count($this->data)) {
			return array_shift($this->data);
		}
		if (!$this->f) {
			return false;
		}
		if (feof($this->f)) {
			$this->f = 0;
			return false;
		}
		$d = fgetcsv($this->f, 10240,$this->delim,'"',"\n");
                if(!empty($d)){
                    foreach($d as &$fd)
                        $fd = iconv($this->enc, "UTF-8//TRANSLIT//IGNORE",$fd);
                    return array(
                        'id' => isset($d[$this->id])?$d[$this->id]:false,
                        'articule' => isset($d[$this->articule])?$d[$this->articule]:false,
                        'price' => isset($d[$this->price])?$d[$this->price]:false,
                        'url' => isset($d[$this->url])?$d[$this->url]:false,
                        'name' => isset($d[$this->name])?$d[$this->name]:false,
                        'category' => isset($d[$this->group])?$d[$this->group]:false,
                        'photo' => isset($d[$this->photo])?$d[$this->photo]:false,
                        'desc' => isset($d[$this->desc])?$d[$this->desc]:false,
                    );
                }else
                    return $this->next();
		$buf = $this->last.fread($this->f, 10240);
		$l = strlen($buf);
		$d = array();
		$s = 0;
		for($i = 0; $i < $l; $i++) {
			if ($buf[$i] == $this->delim) {
				$_s = substr($buf, $s, $i-$s);
				if (strlen($_s)>0 && ($_s[0] == '"') && ($_s[strlen($_s)-1] == '"')) {
					$_s = substr($_s, 1, strlen($_s)-2);
				}
                                $d[] = iconv($this->enc, "UTF-8//TRANSLIT//IGNORE",$_s);
				$s = $i + 1;
				continue;
			}
			if ($buf[$i] == "\n") {
				$_s = str_replace("\r", '', substr($buf, $s, $i-$s));
				if (strlen($_s)>0 && ($_s[0] == '"') && ($_s[strlen($_s)-1] == '"')) {
					$_s = substr($_s, 1, strlen($_s)-2);
				}
                                $d[] = iconv($this->enc, "UTF-8//TRANSLIT//IGNORE",$_s);
				//$d[] = mb_convert_encoding($_s, 'UTF-8', $this->enc);
				$d = array(
                                    'id' => isset($d[$this->id])?$d[$this->id]:false,
                                    'articule' => isset($d[$this->articule])?$d[$this->articule]:false,
                                    'price' => isset($d[$this->price])?$d[$this->price]:false,
                                    'url' => isset($d[$this->url])?$d[$this->url]:false,
                                    'name' => isset($d[$this->name])?$d[$this->name]:false,
                                    'category' => isset($d[$this->group])?$d[$this->group]:false,
                                    'photo' => isset($d[$this->photo])?$d[$this->photo]:false,
                                    'desc' => isset($d[$this->desc])?$d[$this->desc]:false,
				);
				$this->data[] = $d;
				$d = array();
				$s = $i + 1;
			}
		}
		$this->last = substr($buf, $s, $i-$s);
		return $this->next();
	}
        
        public function free() {
            
        }
        
        public function count() {
            return (int)  filesize($this->filename)/1100;
        }
}



class GoodsXlsLoader implements GoodsUploadInterface {
	
	private $reader, $excel;
	private $id, $price, $name, $group, $url, $photo, $get_data, $desc, $articule;
	private $iterator, $sheetit, $sheet;
	
	public function load($filename, $params) {
                require_once(X3::app()->basePath . "/application/extensions/PHPExcel.php");
                
		try {
                        $filename = rtrim($filename,'/ ');
                        $finfo = finfo_open(FILEINFO_MIME);
                        $mimetype = finfo_file($finfo, $filename);
                        finfo_close($finfo);
                        $mimetype = array_shift(explode(';',$mimetype));
                        $ext = '';
                        if(FALSE !== ($aext = array_search($mimetype, Loader::$mime_types)))
                            $ext = $aext;
                        if($ext == 'txt') $ext = 'xml';
                        if($ext == 'xml'){
                            $this->reader = new PHPExcel_Reader_Excel2003XML();
                            $this->reader->load($filename);
                        }else
                            $this->reader = PHPExcel_IOFactory::createReaderForFile($filename);
			if (method_exists($this->reader, 'setReadDataOnly')) {
				$this->reader->setReadDataOnly(true);
			}
                        $params = array_extend(array(
                            'id'=>'','articule'=>'','price'=>'','url'=>'','name'=>'','photo'=>'','group'=>'','group'=>'','desc'=>'','get_data'=>false
                        ),$params);
			$this->id = $params['id'];
			$this->articule = $params['articule'];
			$this->price = $params['price'];
			$this->url = $params['url'];
			$this->name = $params['name'];
			$this->photo = $params['photo'];
			$this->group = $params['group'];
			$this->desc = $params['desc'];
			$this->get_data = $params['get_data'];
			$this->excel = $this->reader->load($filename);
			$this->sheetit = $this->excel->getWorksheetIterator();
                        if(!$this->sheetit) 
                            return false;
		} catch(Exception $e) {
                    echo $e->getMessage();
                    return false;
                    //throw new X3_Exception($e->getMessage());
		}
		return $this;
	}
	
	private static $magic_table = array(
		128 => 'А',
		129 => 'Б',
		130 => 'В',
		131 => 'Г',
		132 => 'Д',
		133 => 'Е',
		134 => 'Ж',
		135 => 'З',
		136 => 'И',
		139 => 'Й',
		138 => 'К',
		139 => 'Л',
		140 => 'М',
		141 => 'Н',
		142 => 'О',
		143 => 'П',
		144 => 'Р',
		145 => 'С',
		146 => 'Т',
		147 => 'У',
		148 => 'Ф',
		149 => 'Х',
		150 => 'Ц',
		151 => 'Ч',
		152 => 'Ш',
		153 => 'Щ',
		154 => 'Ъ',
		155 => 'Ы',
		156 => 'Ь',
		157 => 'Э',
		158 => 'Ю',
		159 => 'Я',
		160 => 'а',
		161 => 'б',
		162 => 'в',
		163 => 'г',
		164 => 'д',
		165 => 'е',
		166 => 'ж',
		167 => 'з',
		168 => 'и',
		169 => 'й',
		170 => 'к',
		171 => 'л',
		172 => 'м',
		173 => 'н',
		174 => 'о',
		175 => 'п',
		176 => 'р',
		177 => 'с',
		178 => 'т',
		179 => 'у',
		180 => 'ф',
		181 => 'х',
		182 => 'ц',
		183 => 'ч',
		184 => 'ш',
		185 => 'щ',
		186 => 'ъ',
		187 => 'ы',
		188 => 'ь',
		189 => 'э',
		190 => 'ю',
		191 => 'я',
	);
	private function magic_enc($s) {
		if (!is_string($s)) {
			return $s;
		}
		$out = '';
		for($i = 0; $i < strlen($s); $i++) {
			if (ord($s[$i]) == 195 && isset(GoodsXlsLoader::$magic_table[ord($s[$i])])) {
                                $i++;
				$out .= GoodsXlsLoader::$magic_table[ord($s[$i])];
			} else {
				$out .= $s[$i];
			}
		}
		return $out;
	}

        public function count() {
            return ($this->sheetit)?$this->sheetit->current()->getHighestRow():0;
        }
	
	public function next() {
		if (!$this->sheetit) {
			return false;
		}
		if (!$this->sheet) {
			$this->sheet = $this->sheetit->current();
		}
                $x = 0;
		while (true) {
				while(true) {
					if ($this->iterator) {
						if (!$this->iterator->valid()) {
							break;
						}
                                                //$f = new PHPExcel_Worksheet;
                                                //$f->getCellCollection();
						$row = $this->iterator->current();
						$cellIterator = $row->getCellIterator();
                                                //$j = $row->getRowIndex();
                                                //$cell_count = PHPExcel_Cell::columnIndexFromString($this->sheet->getHighestColumn());
						$cells = array();
                                                /*for($l=0;$l<$cell_count;$l++){
                                                    //@file_put_contents("uploads/autoload-6.stat", json_encode(array('status'=>'prepare','message'=>$l)));
                                                    $cell = $this->sheet->getCellByColumnAndRow($l, $j);
                                                    $i = $cell->getColumn();
                                                    if (strlen($i) > 1) {
                                                            $i = (ord($i[0])-ord('A')+1)*26+(ord($i[1])-ord('A'));
                                                    } else {
                                                            $i = (ord($i[0])-ord('A'));
                                                    }                                           
                                                    $cells[$i] = $z = GoodsXlsLoader::magic_enc($cell->getValue());
                                                    if (!empty($z) && $z[0] == '=') {
                                                            $cells[$i] = $cell->getCalculatedValue();
                                                    }
                                                }*/
						foreach ($cellIterator as $l=>$cell) {
							$i = $cell->getColumn();
							if (strlen($i) > 1) {
								$i = (ord($i[0])-ord('A')+1)*26+(ord($i[1])-ord('A'));
							} else {
								$i = (ord($i[0])-ord('A'));
							}
							$cells[$i] = $z = GoodsXlsLoader::magic_enc($cell->getValue());
                                                        if (!empty($z) && $z[0] == '=') {
                                                                $cells[$i] = $cell->getCalculatedValue();
                                                        }
						}
                                                
						if ($this->get_data) {
							$data = array();
							foreach($this->get_data as $_k=>$_d) {
								$data[$_k] = $cells[$_d];
							}
						} else {
							$data = array(
								'id' => isset($cells[$this->id])?$cells[$this->id]:false,
								'articule' => isset($cells[$this->articule])?$cells[$this->articule]:false,
								'price' => isset($cells[$this->price])?$cells[$this->price]:false,
								'url' => isset($cells[$this->url])?$cells[$this->url]:false,
								'name' => isset($cells[$this->name])?$cells[$this->name]:false,
								'category' => isset($cells[$this->group])?$cells[$this->group]:false,
								'photo' => isset($cells[$this->photo])?$cells[$this->photo]:false,
								'desc' => isset($cells[$this->desc])?$cells[$this->desc]:false,
							);
						}
						$this->iterator->next();
						return $data;
					} else {
						$this->iterator = $this->sheet->getRowIterator();
					}
				}
			$this->iterator = null;
			$this->sheetit->next();
			if (!$this->sheetit->valid()) {
				unset($this->sheetit);
				unset($this->sheet);
				unset($this->iterator);
				unset($this->excel);
				unset($this->reader);
				return false;
			}
			$this->sheet = $this->sheetit->current();
		}
	}
        
        public function free() {
            unset($this->iterator,$this->sheetit,$this->sheet,$this->reader,$this->excel);
        }
}

class GoodsXmlLoader implements GoodsUploadInterface {
	public $containter;
	public $id;
	public $articule;
	public $price;
	public $name;
	public $url;
	public $photo;
	public $filename;
	public $handler;
	private $cdata = array();
	private $data;
	private $started = false;
	private $cats = array();
	private $catname;
	private $catid;
	private $desc;
	private $lastdata;
	private $bitrix = false;
	private $initialised = false;
	private $get_data = false;
	private $f, $parser;
	/**
	 * Default constructor //TODO: don't like it, consider refactoring
	 * @param unknown_type $container
	 * @param unknown_type $id
	 * @param unknown_type $price
	 * @param unknown_type $url
	 * @param unknown_type $name
	 * @param unknown_type $filename
	 * @param unknown_type $handler
	 */
	public function __construct() {

	}
	
	/**
	 * Start parsing file
	 */
	public function parse() {
		if (!$this->initialised) {
			return;
		}
		while(!count($this->cdata) && !feof($this->f)) {
			$buf = fread($this->f, 10240);
			xml_parse($this->parser, $buf, (strlen($buf) < 10240));
		}
		if (feof($this->f)) {
			fclose($this->f);
			xml_parser_free($this->parser);
			$this->f = false;
			$this->parser = false;
			$this->initialised = false;
		}
	}
	
	public function tag_start($parser, $name, $attrs) {
		//bitrix holy shit
		if ($name == 'Группа') {
			$this->cat = true;
		}
		if (($name == 'Группы') && $this->cat && $this->catid && $this->catname) {
			$this->cats[$this->catid] = $this->catname;
			$this->bitrix = true;
			$this->catid = false;
			$this->catname = false;
			$this->cat = false;
		}
		//yml stuff..
		if (($name == 'category') && isset($attrs['id'])) {
			$this->catid = $attrs['id'];
			$this->cat = true; 
		}
		if ($this->started) {
			$this->data[$name] = array('_attributes' => $attrs, '_head' => &$this->data);
			$this->data = &$this->data[$name];
			return;
		}
		if ($name == ($this->containter)) {
			$this->started = true;
			$this->data = array('_attributes' => $attrs);
		}
	}
	
	public function tag_data($parser, $data) {
		if (!isset($this->data['_data'])) {
			$this->data['_data'] = '';
		}
		$this->data['_data'] .= $data;
		$this->lastdata .= $data;
	}
	
	public function tag_end($parser, $name) {
		//Magic and hateress
		if (($name == 'Ид') && ($this->cat)) {
			$this->catid = trim($this->lastdata);
		}
		if (($name == 'Наименование') && ($this->cat)) {
			$this->catname = trim($this->lastdata);
		}
		if (($name == 'Группа') && $this->cat && $this->catid && $this->catname) {
			$this->cats[$this->catid] = $this->catname;
			$this->bitrix = true;
			$this->catid = false;
			$this->catname = false;
			$this->cat = false;
		}
		if (($name == 'category') && $this->cat && $this->catid) {
			$this->cats[$this->catid] = trim($this->lastdata);
			$this->catid = false;
			$this->catname = false;
			$this->cat = false;
		}
		$this->lastdata = '';
		if ($name == ($this->containter)) {
			if ($this->get_data) {
				$cbdata = array();
				foreach($this->get_data as $_k=>$_d) {
					$cbdata[$_k] = $this->get_by_path($_d);
				}
			} else {
				$cbdata = array(
					'id' => $this->get_by_path($this->id),
					'articule' => $this->get_by_path($this->articule),
					'url' => $this->get_by_path($this->url),
					'price' => $this->get_by_path($this->price),
					'name' => $this->get_by_path($this->name),
					'photo' => $this->get_by_path($this->photo),
					'desc' => $this->get_by_path($this->desc),
				);
			}
			if (count($this->cats)) {
				if ($this->bitrix) {
					$cat = $this->cats[trim($this->get_by_path('Группы.Ид'))];
				} else {
					$cat = $this->cats[trim($this->get_by_path('categoryId'))];
				}
				$cbdata['category'] = $cat;
			}
			$this->cdata[] = $cbdata;
			$this->started = false;
		}
		if ($this->started) {
			$this->data = &$this->data['_head'];
		}
	}
	
	private function get_by_path($p) {
		if (!$p) {
			return '';
		}
		$a = explode('.', $p);
		//print_r($this->data);
		$d =& $this->data;
		$l = count($a)-1;
		if (($a[0] == $this->containter) && $l) {
			array_shift($a);
			$l--;
		}
		foreach($a as $k => $v) {
			if ($k == $l) {
				if (isset($d['_attributes'][$v])) {
					return $d['_attributes'][$v];
				}
			}
			$d =& $d[$v];
		}
		return isset($d['_data'])?$d['_data']:null;
	}

        public function count() {
            return (int)filesize($this->filename)/1500;
            //return count($this->cdata);
        }
	
	public function load($filename, $params) {
		$this->containter = $params['container'];
		$this->id = $params['id'];
		$this->articule = $params['articule'];
		$this->price = $params['price'];
		$this->url = $params['url'];
		$this->name = $params['name'];
		$this->photo = $params['photo'];
		$this->desc = $params['desc'];
		$this->get_data = isset($params['get_data'])?$params['get_data']:false;
		$this->filename = $filename;
		$this->parser = xml_parser_create();
		$this->cdata = array();
		$this->lastdata = '';
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, "tag_start", "tag_end"); 
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_character_data_handler($this->parser, "tag_data");
		$this->f = fopen($this->filename, 'r');
		if (!$this->f) {
			xml_parser_free($this->parser);
			$this->parser = false;
			return false;
		}
		$this->initialised = true;
		return $this;
	}
	
	public function next() {
		$this->parse();
		if (!count($this->cdata)) {
			return false;
		}
		return array_shift($this->cdata);
	}
        
        public function free() {
            unset($this->cdata);
            $this->cdata = array();
        }
}