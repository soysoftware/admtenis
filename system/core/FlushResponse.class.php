<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

class FlushResponse {
	protected static $_response = null;

	protected static function getInstance(){
		if(!isset(self::$_response)){
			self::$_response = new FlushResponse();
		}
		return self::$_response;
	}

	/**
	 * Holds HTTP response statuses
	 *
	 * @var array
	 */
	protected $_statusCodes = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Large',
		415 => 'Unsupported Media Type',
		416 => 'Requested range not satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Time-out'
	);

	/**
	 * Holds known mime type mappings
	 *
	 * @var array
	 */
	protected $_mimeTypes = array(
		'html' => array('text/html', '*/*'),
		'json' => 'application/json',
		'xml' => array('application/xml', 'text/xml'),
		'rss' => 'application/rss+xml',
		'ai' => 'application/postscript',
		'bcpio' => 'application/x-bcpio',
		'bin' => 'application/octet-stream',
		'ccad' => 'application/clariscad',
		'cdf' => 'application/x-netcdf',
		'class' => 'application/octet-stream',
		'cpio' => 'application/x-cpio',
		'cpt' => 'application/mac-compactpro',
		'csh' => 'application/x-csh',
		'csv' => array('text/csv', 'application/vnd.ms-excel', 'text/plain'),
		'dcr' => 'application/x-director',
		'dir' => 'application/x-director',
		'dms' => 'application/octet-stream',
		'doc' => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'drw' => 'application/drafting',
		'dvi' => 'application/x-dvi',
		'dwg' => 'application/acad',
		'dxf' => 'application/dxf',
		'dxr' => 'application/x-director',
		'eot' => 'application/vnd.ms-fontobject',
		'eps' => 'application/postscript',
		'exe' => 'application/octet-stream',
		'ez' => 'application/andrew-inset',
		'flv' => 'video/x-flv',
		'gtar' => 'application/x-gtar',
		'gz' => 'application/x-gzip',
		'bz2' => 'application/x-bzip',
		'7z' => 'application/x-7z-compressed',
		'hdf' => 'application/x-hdf',
		'hqx' => 'application/mac-binhex40',
		'ico' => 'image/vnd.microsoft.icon',
		'ips' => 'application/x-ipscript',
		'ipx' => 'application/x-ipix',
		'js' => 'text/javascript',
		'latex' => 'application/x-latex',
		'lha' => 'application/octet-stream',
		'lsp' => 'application/x-lisp',
		'lzh' => 'application/octet-stream',
		'man' => 'application/x-troff-man',
		'me' => 'application/x-troff-me',
		'mif' => 'application/vnd.mif',
		'ms' => 'application/x-troff-ms',
		'nc' => 'application/x-netcdf',
		'oda' => 'application/oda',
		'otf' => 'font/otf',
		'pdf' => 'application/pdf',
		'pgn' => 'application/x-chess-pgn',
		'pot' => 'application/vnd.ms-powerpoint',
		'pps' => 'application/vnd.ms-powerpoint',
		'ppt' => 'application/vnd.ms-powerpoint',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'ppz' => 'application/vnd.ms-powerpoint',
		'pre' => 'application/x-freelance',
		'prt' => 'application/pro_eng',
		'ps' => 'application/postscript',
		'roff' => 'application/x-troff',
		'scm' => 'application/x-lotusscreencam',
		'set' => 'application/set',
		'sh' => 'application/x-sh',
		'shar' => 'application/x-shar',
		'sit' => 'application/x-stuffit',
		'skd' => 'application/x-koan',
		'skm' => 'application/x-koan',
		'skp' => 'application/x-koan',
		'skt' => 'application/x-koan',
		'smi' => 'application/smil',
		'smil' => 'application/smil',
		'sol' => 'application/solids',
		'spl' => 'application/x-futuresplash',
		'src' => 'application/x-wais-source',
		'step' => 'application/STEP',
		'stl' => 'application/SLA',
		'stp' => 'application/STEP',
		'sv4cpio' => 'application/x-sv4cpio',
		'sv4crc' => 'application/x-sv4crc',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',
		'swf' => 'application/x-shockwave-flash',
		't' => 'application/x-troff',
		'tar' => 'application/x-tar',
		'tcl' => 'application/x-tcl',
		'tex' => 'application/x-tex',
		'texi' => 'application/x-texinfo',
		'texinfo' => 'application/x-texinfo',
		'tr' => 'application/x-troff',
		'tsp' => 'application/dsptype',
		'ttf' => 'font/ttf',
		'unv' => 'application/i-deas',
		'ustar' => 'application/x-ustar',
		'vcd' => 'application/x-cdlink',
		'vda' => 'application/vda',
		'xlc' => 'application/vnd.ms-excel',
		'xll' => 'application/vnd.ms-excel',
		'xlm' => 'application/vnd.ms-excel',
		'xls' => 'application/vnd.ms-excel',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xlw' => 'application/vnd.ms-excel',
		'zip' => 'application/zip',
		'aif' => 'audio/x-aiff',
		'aifc' => 'audio/x-aiff',
		'aiff' => 'audio/x-aiff',
		'au' => 'audio/basic',
		'kar' => 'audio/midi',
		'mid' => 'audio/midi',
		'midi' => 'audio/midi',
		'mp2' => 'audio/mpeg',
		'mp3' => 'audio/mpeg',
		'mpga' => 'audio/mpeg',
		'ogg' => 'audio/ogg',
		'oga' => 'audio/ogg',
		'spx' => 'audio/ogg',
		'ra' => 'audio/x-realaudio',
		'ram' => 'audio/x-pn-realaudio',
		'rm' => 'audio/x-pn-realaudio',
		'rpm' => 'audio/x-pn-realaudio-plugin',
		'snd' => 'audio/basic',
		'tsi' => 'audio/TSP-audio',
		'wav' => 'audio/x-wav',
		'aac' => 'audio/aac',
		'asc' => 'text/plain',
		'c' => 'text/plain',
		'cc' => 'text/plain',
		'css' => 'text/css',
		'etx' => 'text/x-setext',
		'f' => 'text/plain',
		'f90' => 'text/plain',
		'h' => 'text/plain',
		'hh' => 'text/plain',
		'htm' => array('text/html', '*/*'),
		'ics' => 'text/calendar',
		'm' => 'text/plain',
		'rtf' => 'text/rtf',
		'rtx' => 'text/richtext',
		'sgm' => 'text/sgml',
		'sgml' => 'text/sgml',
		'tsv' => 'text/tab-separated-values',
		'tpl' => 'text/template',
		'txt' => 'text/plain',
		'text' => 'text/plain',
		'avi' => 'video/x-msvideo',
		'fli' => 'video/x-fli',
		'mov' => 'video/quicktime',
		'movie' => 'video/x-sgi-movie',
		'mpe' => 'video/mpeg',
		'mpeg' => 'video/mpeg',
		'mpg' => 'video/mpeg',
		'qt' => 'video/quicktime',
		'viv' => 'video/vnd.vivo',
		'vivo' => 'video/vnd.vivo',
		'ogv' => 'video/ogg',
		'webm' => 'video/webm',
		'mp4' => 'video/mp4',
		'gif' => 'image/gif',
		'ief' => 'image/ief',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'pbm' => 'image/x-portable-bitmap',
		'pgm' => 'image/x-portable-graymap',
		'png' => 'image/png',
		'pnm' => 'image/x-portable-anymap',
		'ppm' => 'image/x-portable-pixmap',
		'ras' => 'image/cmu-raster',
		'rgb' => 'image/x-rgb',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'xbm' => 'image/x-xbitmap',
		'xpm' => 'image/x-xpixmap',
		'xwd' => 'image/x-xwindowdump',
		'ice' => 'x-conference/x-cooltalk',
		'iges' => 'model/iges',
		'igs' => 'model/iges',
		'mesh' => 'model/mesh',
		'msh' => 'model/mesh',
		'silo' => 'model/mesh',
		'vrml' => 'model/vrml',
		'wrl' => 'model/vrml',
		'mime' => 'www/mime',
		'pdb' => 'chemical/x-pdb',
		'xyz' => 'chemical/x-pdb',
		'javascript' => 'text/javascript',
		'form' => 'application/x-www-form-urlencoded',
		'file' => 'multipart/form-data',
		'xhtml'	=> array('application/xhtml+xml', 'application/xhtml', 'text/xhtml'),
		'xhtml-mobile'	=> 'application/vnd.wap.xhtml+xml',
		'atom' => 'application/atom+xml',
		'amf' => 'application/x-amf',
		'wap' => array('text/vnd.wap.wml', 'text/vnd.wap.wmlscript', 'image/vnd.wap.wbmp'),
		'wml' => 'text/vnd.wap.wml',
		'wmlscript' => 'text/vnd.wap.wmlscript',
		'wbmp' => 'image/vnd.wap.wbmp',
	);

	/**
	 * Protocol header to send to the client
	 *
	 * @var string
	 */
	protected $_protocol = 'HTTP/1.1';

	/**
	 * Status code to send to the client
	 *
	 * @var integer
	 */
	protected $_status = 200;

	/**
	 * Content type to send. This can be an 'extension' that will be transformed using the $_mimetypes array
	 * or a complete mime-type
	 *
	 * @var integer
	 */
	protected $_contentType = 'application/json';

	/**
	 * Buffer list of headers
	 *
	 * @var array
	 */
	protected $_headers = array();

	/**
	 * Buffer string for response message
	 *
	 * @var string
	 */
	protected $_body = null;

	/**
	 * The charset the response body is encoded with
	 *
	 * @var string
	 */
	protected $_charset = 'UTF-8';

	/**
	 * Holds cookies to be sent to the client
	 *
	 * @var array
	 */
	protected $_cookies = array();

	//Methods

	/**
	 * Class constructor
	 */
	public function __construct() {
	}

	/**
	 * Send wrapper
	 */
	public static function send() {
		self::getInstance()->_send();
	}

	/**
	 * Sends the complete response to the client including headers and message body.
	 * Will echo out the content in the response body.
	 *
	 * @return void
	 */
	protected function _send() {
		if (isset($this->_headers['Location']) && $this->_status === 200) {
			$this->statusCode(302);
		}

		$codeMessage = $this->_statusCodes[$this->_status];
		$this->_setCookies();
		$this->_sendHeader("{$this->_protocol} {$this->_status} {$codeMessage}");
		$this->_setContent();
		$this->_setContentLength();
		$this->_setContentType();
		foreach ($this->_headers as $header => $value) {
			$this->_sendHeader($header, $value);
		}
		$this->_sendContent($this->_body);
	}

	/**
	 * Sets the cookies that have been added via static method CakeResponse::addCookie()
	 * before any other output is sent to the client.
	 * Will set the cookies in the order they have been set.
	 *
	 * @return void
	 */
	protected function _setCookies() {
		foreach ($this->_cookies as $name => $c) {
			setcookie(
				$name, $c['value'], $c['expire'], $c['path'],
				$c['domain'], $c['secure'], $c['httpOnly']
			);
		}
	}

	/**
	 * Formats the Content-Type header based on the configured contentType and charset
	 * the charset will only be set in the header if the response is of type text/*
	 *
	 * @return void
	 */
	protected function _setContentType() {
		if (in_array($this->_status, array(304, 204))) {
			return;
		}
		if (strpos($this->_contentType, 'text/') === 0) {
			$this->header('Content-Type', "{$this->_contentType}; charset={$this->_charset}");
		} elseif ($this->_contentType === 'application/json') {
			$this->header('Content-Type', "{$this->_contentType}; charset=UTF-8");
		} else {
			$this->header('Content-Type', "{$this->_contentType}");
		}
	}

	/**
	 * Sets the response body to an empty text if the status code is 204 or 304
	 *
	 * @return void
	 */
	protected function _setContent() {
		if (in_array($this->_status, array(304, 204))) {
			$this->body('');
		}
	}

	/**
	 * Calculates the correct Content-Length and sets it as a header in the response
	 * Will not set the value if already set or if the output is compressed.
	 *
	 * @return void
	 */
	protected function _setContentLength() {
		$shouldSetLength = !isset($this->_headers['Content-Length']) && !in_array($this->_status, range(301, 307));
		if (isset($this->_headers['Content-Length']) && $this->_headers['Content-Length'] === false) {
			unset($this->_headers['Content-Length']);
			return;
		}
		if ($shouldSetLength) {
			$offset = ob_get_level() ? ob_get_length() : 0;
			if (ini_get('mbstring.func_overload') & 2 && function_exists('mb_strlen')) {
				$this->length($offset + mb_strlen($this->_body, '8bit'));
			} else {
				$this->length($this->_headers['Content-Length'] = $offset + strlen($this->_body));
			}
		}
	}

	/**
	 * Sends a header to the client.
	 *
	 * @param string $name the header name
	 * @param string $value the header value
	 * @return void
	 */
	protected function _sendHeader($name, $value = null) {
		if (!headers_sent()) {
			if (is_null($value)) {
				header($name);
			} else {
				header("{$name}: {$value}");
			}
		}
	}

	/**
	 * Sends a content string to the client.
	 *
	 * @param string $content string to send as response body
	 * @return void
	 */
	protected function _sendContent($content) {
		echo $content;
	}

	/**
	 * Header wrapper
	 */
	public static function header($header = null, $value = null) {
		return self::getInstance()->_header($header, $value);
	}

	/**
	 * Buffers a header string to be sent
	 * Returns the complete list of buffered headers
	 *
	 * ### Single header
	 * e.g `header('Location', 'http://example.com');`
	 *
	 * ### Multiple headers
	 * e.g `header(array('Location' => 'http://example.com', 'X-Extra' => 'My header'));`
	 *
	 * ### String header
	 * e.g `header('WWW-Authenticate: Negotiate');`
	 *
	 * ### Array of string headers
	 * e.g `header(array('WWW-Authenticate: Negotiate', 'Content-type: application/pdf'));`
	 *
	 * Multiple calls for setting the same header name will have the same effect as setting the header once
	 * with the last value sent for it
	 *  e.g `header('WWW-Authenticate: Negotiate'); header('WWW-Authenticate: Not-Negotiate');`
	 * will have the same effect as only doing `header('WWW-Authenticate: Not-Negotiate');`
	 *
	 * @param string|array $header. An array of header strings or a single header string
	 *	- an associative array of "header name" => "header value" is also accepted
	 *	- an array of string headers is also accepted
	 * @param string $value. The header value.
	 * @return array list of headers to be sent
	 */
	protected function _header($header, $value) {
		if (is_null($header)) {
			return $this->_headers;
		}
		if (is_array($header)) {
			foreach ($header as $h => $v) {
				if (is_numeric($h)) {
					$this->header($v);
					continue;
				}
				$this->_headers[$h] = trim($v);
			}
			return $this->_headers;
		}

		if (!is_null($value)) {
			$this->_headers[$header] = $value;
			return $this->_headers;
		}

		list($header, $value) = explode(':', $header, 2);
		$this->_headers[$header] = trim($value);
		return $this->_headers;
	}

	/**
	 * Body wrapper
	 */
	public static function body($content = null) {
		return self::getInstance()->_body($content);
	}

	/**
	 * Buffers the response message to be sent
	 * if $content is null the current buffer is returned
	 *
	 * @param string $content the string message to be sent
	 * @return string current message buffer if $content param is passed as null
	 */
	protected function _body($content) {
		if (is_null($content)) {
			return $this->_body;
		}
		return $this->_body = $content;
	}

	/**
	 * statusCode wrapper
	 */
	public static function statusCode($code = null) {
		return self::getInstance()->_statusCode($code);
	}

	/**
	 * Sets the HTTP status code to be sent
	 * if $code is null the current code is returned
	 *
	 * @param integer $code
	 * @return integer current status code
	 * @throws Exception When an unknown status code is reached.
	 */
	protected function _statusCode($code) {
		if (is_null($code)) {
			return $this->_status;
		}
		if (!isset($this->_statusCodes[$code])) {
			throw new Exception('Unknown status code');
		}
		return $this->_status = $code;
	}

	/**
	 * Type wrapper
	 */
	public static function type($contentType = null) {
		return self::getInstance()->_type($contentType);
	}

	/**
	 * Sets the response content type. It can be either a file extension
	 * which will be mapped internally to a mime-type or a string representing a mime-type
	 * if $contentType is null the current content type is returned
	 * if $contentType is an associative array, content type definitions will be stored/replaced
	 *
	 * ### Setting the content type
	 *
	 * e.g `type('jpg');`
	 *
	 * ### Returning the current content type
	 *
	 * e.g `type();`
	 *
	 * ### Storing content type definitions
	 *
	 * e.g `type(array('keynote' => 'application/keynote', 'bat' => 'application/bat'));`
	 *
	 * ### Replacing a content type definition
	 *
	 * e.g `type(array('jpg' => 'text/plain'));`
	 *
	 * @param string $contentType
	 * @return mixed current content type or false if supplied an invalid content type
	 */
	protected function _type($contentType) {
		if (is_null($contentType)) {
			return $this->_contentType;
		}
		if (is_array($contentType)) {
			foreach ($contentType as $type => $definition) {
				$this->_mimeTypes[$type] = $definition;
			}
			return $this->_contentType;
		}
		if (isset($this->_mimeTypes[$contentType])) {
			$contentType = $this->_mimeTypes[$contentType];
			$contentType = is_array($contentType) ? current($contentType) : $contentType;
		}
		if (strpos($contentType, '/') === false) {
			return false;
		}
		return $this->_contentType = $contentType;
	}

	/**
	 * charset wrapper
	 */
	public static function charset($charset = null) {
		return self::getInstance()->_type($charset);
	}

	/**
	 * Sets the response charset
	 * if $charset is null the current charset is returned
	 *
	 * @param string $charset
	 * @return string current charset
	 */
	protected function _charset($charset) {
		if (is_null($charset)) {
			return $this->_charset;
		}
		return $this->_charset = $charset;
	}

	/**
	 * expires wrapper
	 */
	public static function expires($time = null) {
		return self::getInstance()->_type($time);
	}

	/**
	 * Sets the Expires header for the response by taking an expiration time
	 * If called with no parameters it will return the current Expires value
	 *
	 * ## Examples:
	 *
	 * `$response->expires('now')` Will Expire the response cache now
	 * `$response->expires(new DateTime('+1 day'))` Will set the expiration in next 24 hours
	 * `$response->expires()` Will return the current expiration header value
	 *
	 * @param string|DateTime $time
	 * @return string
	 */
	protected function _expires($time) {
		if ($time !== null) {
			$date = $this->_getUTCDate($time);
			$this->_headers['Expires'] = $date->format('D, j M Y H:i:s') . ' GMT';
		}
		if (isset($this->_headers['Expires'])) {
			return $this->_headers['Expires'];
		}
		return null;
	}

	/**
	 * Returns a DateTime object initialized at the $time param and using UTC
	 * as timezone
	 *
	 * @param string|integer|DateTime $time
	 * @return DateTime
	 */
	protected function _getUTCDate($time = null) {
		if ($time instanceof DateTime) {
			$result = clone $time;
		} elseif (is_integer($time)) {
			$result = new DateTime(date('Y-m-d H:i:s', $time));
		} else {
			$result = new DateTime($time);
		}
		$result->setTimeZone(new DateTimeZone('UTC'));
		return $result;
	}

	/**
	 * expires wrapper
	 */
	public static function download($filename) {
		self::getInstance()->_download($filename);
	}

	/**
	 * Sets the correct headers to instruct the browser to download the response as a file.
	 *
	 * @param string $filename the name of the file as the browser will download the response
	 * @return void
	 */
	protected function _download($filename) {
		$this->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
	}

	/**
	 * expires wrapper
	 */
	public static function protocol($protocol = null) {
		return self::getInstance()->_protocol($protocol);
	}

	/**
	 * Sets the protocol to be used when sending the response. Defaults to HTTP/1.1
	 * If called with no arguments, it will return the current configured protocol
	 *
	 * @return string protocol to be used for sending response
	 */
	protected function _protocol($protocol) {
		if ($protocol !== null) {
			$this->_protocol = $protocol;
		}
		return $this->_protocol;
	}

	/**
	 * expires wrapper
	 */
	public static function length($bytes = null) {
		return self::getInstance()->_length($bytes);
	}

	/**
	 * Sets the Content-Length header for the response
	 * If called with no arguments returns the last Content-Length set
	 *
	 * @return int
	 */
	protected function _length($bytes) {
		if ($bytes !== null ) {
			$this->_headers['Content-Length'] = $bytes;
		}
		if (isset($this->_headers['Content-Length'])) {
			return $this->_headers['Content-Length'];
		}
		return null;
	}
}


?>
