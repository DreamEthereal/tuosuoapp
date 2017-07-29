<?php
//dezend by http://www.yunlu99.com/
class HTML_TO_DOC
{
	public $docFile = '';
	public $title = '';
	public $htmlHead = '';
	public $htmlBody = '';

	public function HTML_TO_DOC()
	{
		$this->title = 'Untitled Document';
		$this->htmlHead = '';
		$this->htmlBody = '';
	}

	public function setDocFileName($docfile)
	{
		$this->docFile = $docfile;

		if (!preg_match('/\\.doc$/i', $this->docFile)) {
			$this->docFile .= '.doc';
		}

		return NULL;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getHeader()
	{
		$_obf_lWk5hHye = '			 <html xmlns:v="urn:schemas-microsoft-com:vml"' . "\r\n" . '			xmlns:o="urn:schemas-microsoft-com:office:office"' . "\r\n" . '			xmlns:w="urn:schemas-microsoft-com:office:word"' . "\r\n" . '			xmlns="http://www.w3.org/TR/REC-html40">' . "\r\n" . '			' . "\r\n" . '			<head>' . "\r\n" . '			<meta http-equiv=Content-Type content="text/html; charset=gbk">' . "\r\n" . '			<meta name=ProgId content=Word.Document>' . "\r\n" . '			<meta name=Generator content="Microsoft Word 9">' . "\r\n" . '			<meta name=Originator content="Microsoft Word 9">' . "\r\n" . '			<!--[if !mso]>' . "\r\n" . '			<style>' . "\r\n" . '			v\\:* {behavior:url(#default#VML);}' . "\r\n" . '			o\\:* {behavior:url(#default#VML);}' . "\r\n" . '			w\\:* {behavior:url(#default#VML);}' . "\r\n" . '			.shape {behavior:url(#default#VML);}' . "\r\n" . '			</style>' . "\r\n" . '			<![endif]-->' . "\r\n" . '			<title>' . $this->title . '</title>' . "\r\n" . '			<!--[if gte mso 9]><xml>' . "\r\n" . '			 <w:WordDocument>' . "\r\n" . '			  <w:View>Print</w:View>' . "\r\n" . '			  <w:DoNotHyphenateCaps/>' . "\r\n" . '			  <w:PunctuationKerning/>' . "\r\n" . '			  <w:DrawingGridHorizontalSpacing>9.35 pt</w:DrawingGridHorizontalSpacing>' . "\r\n" . '			  <w:DrawingGridVerticalSpacing>9.35 pt</w:DrawingGridVerticalSpacing>' . "\r\n" . '			 </w:WordDocument>' . "\r\n" . '			</xml><![endif]-->' . "\r\n" . '			<style>' . "\r\n" . '			<!--' . "\r\n" . '			 /* Font Definitions */' . "\r\n" . '			@font-face' . "\r\n" . '				{font-family:Calibri;' . "\r\n" . '				panose-1:2 11 6 4 3 5 4 4 2 4;' . "\r\n" . '				mso-font-charset:0;' . "\r\n" . '				mso-generic-font-family:swiss;' . "\r\n" . '				mso-font-pitch:variable;' . "\r\n" . '				mso-font-signature:536871559 0 0 0 415 0;}' . "\r\n" . '			 /* Style Definitions */' . "\r\n" . '			p.MsoNormal, li.MsoNormal, div.MsoNormal' . "\r\n" . '				{mso-style-parent:"";' . "\r\n" . '				margin:0in;' . "\r\n" . '				margin-bottom:.0001pt;' . "\r\n" . '				mso-pagination:widow-orphan;' . "\r\n" . '				font-size:7.5pt;' . "\r\n" . '			        mso-bidi-font-size:8.0pt;' . "\r\n" . '				font-family:"Calibri";' . "\r\n" . '				mso-fareast-font-family:"Calibri";}' . "\r\n" . '			p.small' . "\r\n" . '				{mso-style-parent:"";' . "\r\n" . '				margin:0in;' . "\r\n" . '				margin-bottom:.0001pt;' . "\r\n" . '				mso-pagination:widow-orphan;' . "\r\n" . '				font-size:1.0pt;' . "\r\n" . '			        mso-bidi-font-size:1.0pt;' . "\r\n" . '				font-family:"Calibri";' . "\r\n" . '				mso-fareast-font-family:"Calibri";}' . "\r\n" . '			@page Section1' . "\r\n" . '				{size:8.5in 11.0in;' . "\r\n" . '				margin:1.0in 1.25in 1.0in 1.25in;' . "\r\n" . '				mso-header-margin:.5in;' . "\r\n" . '				mso-footer-margin:.5in;' . "\r\n" . '				mso-paper-source:0;}' . "\r\n" . '			div.Section1' . "\r\n" . '				{page:Section1;}' . "\r\n" . '			-->' . "\r\n" . '			</style>' . "\r\n" . '			<!--[if gte mso 9]><xml>' . "\r\n" . '			 <o:shapedefaults v:ext="edit" spidmax="1032">' . "\r\n" . '			  <o:colormenu v:ext="edit" strokecolor="none"/>' . "\r\n" . '			 </o:shapedefaults></xml><![endif]--><!--[if gte mso 9]><xml>' . "\r\n" . '			 <o:shapelayout v:ext="edit">' . "\r\n" . '			  <o:idmap v:ext="edit" data="1"/>' . "\r\n" . '			 </o:shapelayout></xml><![endif]-->' . "\r\n" . '			 ' . $this->htmlHead . '' . "\r\n" . '			</head>' . "\r\n" . '			<body>';
		return $_obf_lWk5hHye;
	}

	public function getFotter()
	{
		return '</body></html>';
	}

	public function createDocFromURL($url, $file, $download = false)
	{
		if (!preg_match('/^http:/', $url)) {
			$url = 'http://' . $url;
		}

		$_obf_YBY_ = fopen($url, 'r');
		$_obf_HVjSRcHp = '';
		$_obf_lEGQqw__ = '';

		while ($_obf_HVjSRcHp = fgets($_obf_YBY_, 4096)) {
			$_obf_lEGQqw__ = $_obf_lEGQqw__ . $_obf_HVjSRcHp;
		}

		fclose($_obf_YBY_);
		return $this->createDoc($_obf_lEGQqw__, $file, $download);
	}

	public function createDoc($html, $file, $download = false)
	{
		$this->_parseHtml($html);
		$this->setDocFileName($file);
		$_obf__mbX = $this->getHeader();
		$_obf__mbX .= $this->htmlBody;
		$_obf__mbX .= $this->getFotter();

		if ($download) {
			@header('Cache-Control: ');
			@header('Pragma: ');
			@header('Content-type: application/octet-stream;charset=utf8');
			@header('Content-Disposition: attachment; filename="' . $this->docFile . '"');
			echo $_obf__mbX;
			return true;
		}
		else {
			return $this->write_file($this->docFile, $_obf__mbX);
		}
	}

	public function _parseHtml($html)
	{
		$html = preg_replace('/<!DOCTYPE((.|' . "\n" . ')*?)>/ims', '', $html);
		$html = preg_replace('/<script((.|' . "\n" . ')*?)>((.|' . "\n" . ')*?)<\\/script>/ims', '', $html);
		preg_match('/<head>((.|' . "\n" . ')*?)<\\/head>/ims', $html, $_obf_8UmnTppRcA__);
		$_obf__o37TQ__ = $_obf_8UmnTppRcA__[1];
		preg_match('/<title>((.|' . "\n" . ')*?)<\\/title>/ims', $_obf__o37TQ__, $_obf_8UmnTppRcA__);
		$this->title = $_obf_8UmnTppRcA__[1];
		$html = preg_replace('/<head>((.|' . "\n" . ')*?)<\\/head>/ims', '', $html);
		$_obf__o37TQ__ = preg_replace('/<title>((.|' . "\n" . ')*?)<\\/title>/ims', '', $_obf__o37TQ__);
		$_obf__o37TQ__ = preg_replace('/<\\/?head>/ims', '', $_obf__o37TQ__);
		$html = preg_replace('/<\\/?body((.|' . "\n" . ')*?)>/ims', '', $html);
		$this->htmlHead = $_obf__o37TQ__;
		$this->htmlBody = $html;
		return NULL;
	}

	public function write_file($file, $content, $mode = 'w')
	{
		$_obf_YBY_ = @fopen($file, $mode);

		if (!is_resource($_obf_YBY_)) {
			return false;
		}

		fwrite($_obf_YBY_, $content);
		fclose($_obf_YBY_);
		return true;
	}
}


?>
