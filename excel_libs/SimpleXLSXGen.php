<?php
namespace Shuchkin;

/**
 * Class SimpleXLSXGen
 * Export data to clean, styled XLSX with merged title banners, auto-column widths, 
 * typed numbers, center/right/left alignments, and thin cell borders.
 * Zero external dependencies: works with or without ZipArchive extension.
 * Author: Sergey Tsalkov <stsalkov@gmail.com> / Revamped for NXTInfra P2P
 * License: MIT
 */
class SimpleXLSXGen {

    public $sheets = [];

    public function __construct() {
        $this->sheets = [
            [
                'name' => 'Sheet1',
                'rows' => []
            ]
        ];
    }

    public static function fromArray(array $rows, $sheetName = null) {
        $xlsx = new static();
        if ($sheetName === null) {
            $sheetName = 'Sheet1';
        }
        $xlsx->sheets[0] = ['name' => $sheetName, 'rows' => $rows];
        return $xlsx;
    }

    public function addSheet(array $rows, $name = null) {
        if ($name === null) {
            $name = 'Sheet' . (count($this->sheets) + 1);
        }
        if (count($this->sheets) === 1 && empty($this->sheets[0]['rows'])) {
            $this->sheets[0] = ['name' => $name, 'rows' => $rows];
        } else {
            $this->sheets[] = ['name' => $name, 'rows' => $rows];
        }
        return $this;
    }

    public function downloadAs($filename) {
        if (strrpos(strtolower($filename), '.xlsx') !== strlen($filename) - 5) {
            $filename .= '.xlsx';
        }
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        echo $this->output();
        exit();
    }

    public function saveAs($filename) {
        return (bool) file_put_contents($filename, $this->output());
    }

    public function output() {
        $files = [
            '[Content_Types].xml' => $this->buildContentTypes(),
            '_rels/.rels' => $this->buildRels(),
            'xl/_rels/workbook.xml.rels' => $this->buildWorkbookRels(),
            'xl/workbook.xml' => $this->buildWorkbook(),
            'xl/styles.xml' => $this->buildStyles()
        ];
        foreach ($this->sheets as $idx => $sheet) {
            $files['xl/worksheets/sheet' . ($idx + 1) . '.xml'] = $this->buildSheet($sheet['rows']);
        }

        if (class_exists('\ZipArchive')) {
            $zip = new \ZipArchive();
            $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
            if ($zip->open($tempFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                foreach ($files as $name => $content) {
                    $zip->addFromString($name, $content);
                }
                $zip->close();
                $data = file_get_contents($tempFile);
                @unlink($tempFile);
                if ($data !== false && strlen($data) > 0) {
                    return $data;
                }
            }
        }

        // Pure PHP ZIP builder (using zlib / gzdeflate)
        return $this->buildZip($files);
    }

    public function __toString() {
        return (string) $this->output();
    }

    protected function buildZip(array $files) {
        $datasec = '';
        $ctrlDir = '';
        $offset = 0;

        $time = time();
        $darray = getdate($time);
        $dosDate = (($darray['year'] - 1980) << 9) | ($darray['mon'] << 5) | $darray['mday'];
        $dosTime = ($darray['hours'] << 11) | ($darray['minutes'] << 5) | ($darray['seconds'] >> 1);

        foreach ($files as $name => $data) {
            $name = str_replace('\\', '/', $name);
            $unc_len = strlen($data);
            $crc = crc32($data);
            $zdata = gzdeflate($data);
            $c_len = strlen($zdata);

            // Local file header (30 bytes + name)
            $fr = "\x50\x4b\x03\x04";
            $fr .= pack('v', 20); // version needed to extract (2.0)
            $fr .= pack('v', 0);  // general purpose bit flag
            $fr .= pack('v', 8);  // compression method (8 = deflate)
            $fr .= pack('v', $dosTime);
            $fr .= pack('v', $dosDate);
            $fr .= pack('V', $crc);
            $fr .= pack('V', $c_len);
            $fr .= pack('V', $unc_len);
            $fr .= pack('v', strlen($name)); // file name length
            $fr .= pack('v', 0); // extra field length
            $fr .= $name;
            $fr .= $zdata;

            $datasec .= $fr;

            // Central directory entry (46 bytes + name)
            $cd = "\x50\x4b\x01\x02";
            $cd .= pack('v', 20); // version made by
            $cd .= pack('v', 20); // version needed to extract
            $cd .= pack('v', 0);  // general purpose bit flag
            $cd .= pack('v', 8);  // compression method
            $cd .= pack('v', $dosTime);
            $cd .= pack('v', $dosDate);
            $cd .= pack('V', $crc);
            $cd .= pack('V', $c_len);
            $cd .= pack('V', $unc_len);
            $cd .= pack('v', strlen($name));
            $cd .= pack('v', 0); // extra field length
            $cd .= pack('v', 0); // file comment length
            $cd .= pack('v', 0); // disk number start
            $cd .= pack('v', 0); // internal file attributes
            $cd .= pack('V', 32); // external file attributes (archive)
            $cd .= pack('V', $offset); // relative offset of local header
            $cd .= $name;

            $ctrlDir .= $cd;
            $offset = strlen($datasec);
        }

        // End of central directory record (22 bytes)
        $eocd = "\x50\x4b\x05\x06";
        $eocd .= pack('v', 0); // number of this disk
        $eocd .= pack('v', 0); // number of the disk with the start of the central directory
        $eocd .= pack('v', count($files)); // total number of entries in the central directory on this disk
        $eocd .= pack('v', count($files)); // total number of entries in the central directory
        $eocd .= pack('V', strlen($ctrlDir)); // size of the central directory
        $eocd .= pack('V', strlen($datasec)); // offset of start of central directory
        $eocd .= pack('v', 0); // .ZIP file comment length

        return $datasec . $ctrlDir . $eocd;
    }

    protected function buildContentTypes() {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $xml .= '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">';
        $xml .= '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>';
        $xml .= '<Default Extension="xml" ContentType="application/xml"/>';
        $xml .= '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>';
        $xml .= '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>';
        foreach ($this->sheets as $idx => $sheet) {
            $xml .= '<Override PartName="/xl/worksheets/sheet' . ($idx + 1) . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
        }
        $xml .= '</Types>';
        return $xml;
    }

    protected function buildRels() {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $xml .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
        $xml .= '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>';
        $xml .= '</Relationships>';
        return $xml;
    }

    protected function buildWorkbookRels() {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $xml .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
        $xml .= '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>';
        foreach ($this->sheets as $idx => $sheet) {
            $xml .= '<Relationship Id="rId' . ($idx + 2) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . ($idx + 1) . '.xml"/>';
        }
        $xml .= '</Relationships>';
        return $xml;
    }

    protected function buildWorkbook() {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $xml .= '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';
        $xml .= '<sheets>';
        foreach ($this->sheets as $idx => $sheet) {
            $name = htmlspecialchars($sheet['name'], ENT_QUOTES, 'UTF-8');
            $xml .= '<sheet name="' . $name . '" sheetId="' . ($idx + 1) . '" r:id="rId' . ($idx + 2) . '"/>';
        }
        $xml .= '</sheets>';
        $xml .= '</workbook>';
        return $xml;
    }

    protected function buildStyles() {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $xml .= '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
        $xml .= '<fonts count="3">';
        $xml .= '<font><sz val="10"/><color rgb="FF000000"/><name val="Calibri"/><family val="2"/></font>';
        $xml .= '<font><b/><sz val="11"/><color rgb="FF000000"/><name val="Calibri"/><family val="2"/></font>';
        $xml .= '<font><b/><sz val="13"/><color rgb="FF000000"/><name val="Calibri"/><family val="2"/></font>';
        $xml .= '</fonts>';
        $xml .= '<fills count="3">';
        $xml .= '<fill><patternFill patternType="none"/></fill>';
        $xml .= '<fill><patternFill patternType="gray125"/></fill>';
        $xml .= '<fill><patternFill patternType="solid"><fgColor rgb="FFE0E0E0"/><bgColor indexed="64"/></patternFill></fill>';
        $xml .= '</fills>';
        $xml .= '<borders count="2">';
        $xml .= '<border><left/><right/><top/><bottom/><diagonal/></border>';
        $xml .= '<border>';
        $xml .= '<left style="thin"><color rgb="FFBFBFBF"/></left>';
        $xml .= '<right style="thin"><color rgb="FFBFBFBF"/></right>';
        $xml .= '<top style="thin"><color rgb="FFBFBFBF"/></top>';
        $xml .= '<bottom style="thin"><color rgb="FFBFBFBF"/></bottom>';
        $xml .= '</border>';
        $xml .= '</borders>';
        $xml .= '<cellStyleXfs count="1">';
        $xml .= '<xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>';
        $xml .= '</cellStyleXfs>';
        $xml .= '<cellXfs count="5">';
        // Style 0: Data Text (Left aligned, thin border, Calibri 10)
        $xml .= '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>';
        // Style 1: Header Cell (Centered, Bold, Grey #E0E0E0 Fill, Border, Calibri 11)
        $xml .= '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>';
        // Style 2: Title Banner (Centered, Bold 13pt)
        $xml .= '<xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>';
        // Style 3: Number / Amount (Right aligned, thin border, Calibri 10)
        $xml .= '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1"><alignment horizontal="right" vertical="center"/></xf>';
        // Style 4: Date / Status / Code (Center aligned, thin border, Calibri 10)
        $xml .= '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>';
        $xml .= '</cellXfs>';
        $xml .= '</styleSheet>';
        return $xml;
    }

    protected function buildSheet(array $rows) {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
        
        $hasTitle = false;
        $maxCols = 0;
        foreach ($rows as $r) {
            $maxCols = max($maxCols, count($r));
        }
        if (count($rows) > 1 && count($rows[0]) === 1 && $maxCols > 1) {
            $hasTitle = true;
        }

        // Auto calculate column widths strictly ignoring the title banner
        $colWidths = [];
        foreach ($rows as $rIdx => $row) {
            if ($hasTitle && $rIdx === 0) {
                continue;
            }
            $cIdx = 0;
            foreach ($row as $val) {
                $len = (function_exists('mb_strlen') ? mb_strlen((string)$val, 'UTF-8') : strlen((string)$val));
                if (!isset($colWidths[$cIdx]) || $len > $colWidths[$cIdx]) {
                    $colWidths[$cIdx] = $len;
                }
                $cIdx++;
            }
        }

        if (!empty($colWidths)) {
            $xml .= '<cols>';
            for ($cIdx = 0; $cIdx < $maxCols; $cIdx++) {
                $colNum = $cIdx + 1;
                $maxLen = $colWidths[$cIdx] ?? 10;
                $width = max(11, min(45, $maxLen + 3));
                $xml .= '<col min="' . $colNum . '" max="' . $colNum . '" width="' . $width . '" customWidth="1"/>';
            }
            $xml .= '</cols>';
        }

        $xml .= '<sheetData>';

        foreach ($rows as $rIdx => $row) {
            $rowNum = $rIdx + 1;

            if ($hasTitle && $rIdx === 0) {
                // Row 1: Title Banner with all cells initialized across table width for flawless merge
                $xml .= '<row r="' . $rowNum . '" ht="28" customHeight="1">';
                for ($cIdx = 0; $cIdx < $maxCols; $cIdx++) {
                    $colLetter = $this->colName($cIdx);
                    $cellRef = $colLetter . $rowNum;
                    if ($cIdx === 0) {
                        $cleanVal = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', (string)($row[0] ?? ''));
                        $valStr = htmlspecialchars($cleanVal, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                        $xml .= '<c r="' . $cellRef . '" t="inlineStr" s="2"><is><t>' . $valStr . '</t></is></c>';
                    } else {
                        $xml .= '<c r="' . $cellRef . '" s="2"/>';
                    }
                }
                $xml .= '</row>';
                continue;
            }

            $isHeader = ($hasTitle && $rIdx === 1) || (!$hasTitle && $rIdx === 0);
            $rowHeightAttr = $isHeader ? ' ht="24" customHeight="1"' : ' ht="20" customHeight="1"';

            $xml .= '<row r="' . $rowNum . '"' . $rowHeightAttr . '>';
            $cIdx = 0;
            foreach ($row as $val) {
                $colLetter = $this->colName($cIdx);
                $cellRef = $colLetter . $rowNum;
                
                if ($isHeader) {
                    $style = ' s="1"';
                    $cleanVal = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', (string)$val);
                    $valStr = htmlspecialchars($cleanVal, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $xml .= '<c r="' . $cellRef . '" t="inlineStr"' . $style . '><is><t>' . $valStr . '</t></is></c>';
                } else {
                    $valStrRaw = trim((string)$val);
                    
                    // 1. Pure Numeric (Amounts, Quantities, Unit Rates, IDs)
                    if (is_numeric($valStrRaw) && !preg_match('/^0[0-9]+$/', $valStrRaw)) {
                        $style = ' s="3"'; // Right-aligned numeric
                        $numVal = (strpos($valStrRaw, '.') !== false) ? (float)$valStrRaw : (int)$valStrRaw;
                        $xml .= '<c r="' . $cellRef . '"' . $style . '><v>' . $numVal . '</v></c>';
                    }
                    // 2. Date in DD-MM-YYYY format
                    else if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $valStrRaw)) {
                        $style = ' s="4"'; // Center-aligned date
                        $xml .= '<c r="' . $cellRef . '" t="inlineStr"' . $style . '><is><t>' . $valStrRaw . '</t></is></c>';
                    }
                    // 3. Short status / code
                    else if (in_array(strtolower($valStrRaw), ['approved', 'pending', 'draft', 'rejected', 'closed', 'partially closed', 'access qty', 'active', 'nos', 'lumpsum', 'job', 'set', 'kg', 'mtr', 'yes', 'no'])) {
                        $style = ' s="4"'; // Center-aligned
                        $cleanVal = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $valStrRaw);
                        $valStr = htmlspecialchars($cleanVal, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                        $xml .= '<c r="' . $cellRef . '" t="inlineStr"' . $style . '><is><t>' . $valStr . '</t></is></c>';
                    }
                    // 4. Standard Text
                    else {
                        $style = ' s="0"'; // Left-aligned
                        $cleanVal = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', (string)$val);
                        $valStr = htmlspecialchars($cleanVal, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                        $xml .= '<c r="' . $cellRef . '" t="inlineStr"' . $style . '><is><t>' . $valStr . '</t></is></c>';
                    }
                }
                $cIdx++;
            }
            $xml .= '</row>';
        }

        $xml .= '</sheetData>';

        if ($hasTitle && $maxCols > 1) {
            $lastColLetter = $this->colName($maxCols - 1);
            $xml .= '<mergeCells count="1">';
            $xml .= '<mergeCell ref="A1:' . $lastColLetter . '1"/>';
            $xml .= '</mergeCells>';
        }

        $xml .= '</worksheet>';
        return $xml;
    }

    protected function colName($n) {
        for ($r = ''; $n >= 0; $n = intval($n / 26) - 1) {
            $r = chr($n % 26 + 65) . $r;
        }
        return $r;
    }
}
