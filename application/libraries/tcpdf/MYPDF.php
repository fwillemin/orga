<?php

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false, $customHeader = '') {
        if ($this->inxobj) {
            // we are inside an XObject template
            return;
        }
        if (!isset($this->original_lMargin) OR $keepmargins) {
            $this->original_lMargin = $this->lMargin;
        }
        if (!isset($this->original_rMargin) OR $keepmargins) {
            $this->original_rMargin = $this->rMargin;
        }
        // terminate previous page
        $this->endPage();
        // start new page
        $this->startPage($orientation, $format, $tocpage, $customHeader);
    }

    public function startPage($orientation = '', $format = '', $tocpage = false, $customHeader = '') {
        if ($tocpage) {
            $this->tocpage = true;
        }
        // move page numbers of documents to be attached
        if ($this->tocpage) {
            // move reference to unexistent pages (used for page attachments)
            // adjust outlines
            $tmpoutlines = $this->outlines;
            foreach ($tmpoutlines as $key => $outline) {
                if (!$outline['f'] AND ( $outline['p'] > $this->numpages)) {
                    $this->outlines[$key]['p'] = ($outline['p'] + 1);
                }
            }
            // adjust dests
            $tmpdests = $this->dests;
            foreach ($tmpdests as $key => $dest) {
                if (!$dest['f'] AND ( $dest['p'] > $this->numpages)) {
                    $this->dests[$key]['p'] = ($dest['p'] + 1);
                }
            }
            // adjust links
            $tmplinks = $this->links;
            foreach ($tmplinks as $key => $link) {
                if (!$link['f'] AND ( $link['p'] > $this->numpages)) {
                    $this->links[$key]['p'] = ($link['p'] + 1);
                }
            }
        }
        if ($this->numpages > $this->page) {
            // this page has been already added
            $this->setPage($this->page + 1);
            $this->SetY($this->tMargin);
            return;
        }
        // start a new page
        if ($this->state == 0) {
            $this->Open();
        }
        ++$this->numpages;
        $this->swapMargins($this->booklet);
        // save current graphic settings
        $gvars = $this->getGraphicVars();
        // start new page
        $this->_beginpage($orientation, $format);
        // mark page as open
        $this->pageopen[$this->page] = true;
        // restore graphic settings
        $this->setGraphicVars($gvars);
        // mark this point
        $this->setPageMark();
        // print page header
        $this->setHeader($customHeader);
        // restore graphic settings
        $this->setGraphicVars($gvars);
        // mark this point
        $this->setPageMark();
        // print table header (if any)
        $this->setTableHeader();
        // set mark for empty page check
        $this->emptypagemrk[$this->page] = $this->pagelen[$this->page];
    }

    protected function setHeader($customHeader = '') {
        if (!$this->print_header OR ( $this->state != 2)) {
            return;
        }
        $this->InHeader = true;
        $this->setGraphicVars($this->default_graphic_vars);
        $temp_thead = $this->thead;
        $temp_theadMargins = $this->theadMargins;
        $lasth = $this->lasth;
        $newline = $this->newline;
        $this->_outSaveGraphicsState();
        $this->rMargin = $this->original_rMargin;
        $this->lMargin = $this->original_lMargin;
        $this->SetCellPadding(0);
        //set current position
        if ($this->rtl) {
            $this->SetXY($this->original_rMargin, $this->header_margin);
        } else {
            $this->SetXY($this->original_lMargin, $this->header_margin);
        }
        $this->SetFont($this->header_font[0], $this->header_font[1], $this->header_font[2]);
        $this->Header($customHeader);
        //restore position
        if ($this->rtl) {
            $this->SetXY($this->original_rMargin, $this->tMargin);
        } else {
            $this->SetXY($this->original_lMargin, $this->tMargin);
        }
        $this->_outRestoreGraphicsState();
        $this->lasth = $lasth;
        $this->thead = $temp_thead;
        $this->theadMargins = $temp_theadMargins;
        $this->newline = $newline;
        $this->InHeader = false;
    }

//Page header
    function Header($contenu = '') {
        if ($this->header_xobjid === false) {
            // start a new XObject Template
            $this->header_xobjid = $this->startTemplate($this->w, $this->tMargin);
            $headerfont = $this->getHeaderFont();
            $headerdata = $this->getHeaderData();
            $this->y = $this->header_margin;
            if ($this->rtl) {
                $this->x = $this->w - $this->original_rMargin;
            } else {
                $this->x = $this->original_lMargin;
            }

            $imgy = $this->y;

            //$cell_height = $this->getCellHeight(800);
            // set starting margin for text data cell
            if ($this->getRTL()) {
                $header_x = $this->original_rMargin;
            } else {
                $header_x = $this->original_lMargin;
            }
            $cw = $this->w - $this->original_lMargin - $this->original_rMargin - ($headerdata['logo_width'] * 1.1);
            $this->SetTextColorArray($this->header_text_color);
            // header title
//            $this->SetFont($headerfont[0], 'B', $headerfont[2] + 1);
//            $this->SetX($header_x);
//            $this->writeHTMLCell($cw, $cell_height, '<table><tr><td>test2</td></tr></table>', 0, 1, '', 0, '', 0);
            // header string
            $this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
            $this->SetX($header_x);
            $this->writeHTMLCell(0, 70, 10, 10, $contenu, 0, 0, FALSE, TRUE, '', TRUE);
            // print an ending header line
//            $this->SetLineStyle(array('width' => 0.85 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $headerdata['line_color']));
//            $this->SetY((2.835 / $this->k) + max($imgy, $this->y));
//            if ($this->rtl) {
//                $this->SetX($this->original_rMargin);
//            } else {
//                $this->SetX($this->original_lMargin);
//            }
            //$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
            $this->endTemplate();
        }
        // print header template
        $x = 0;
        $dx = 0;
        if (!$this->header_xobj_autoreset AND $this->booklet AND ( ($this->page % 2) == 0)) {
            // adjust margins for booklet mode
            $dx = ($this->original_lMargin - $this->original_rMargin);
        }
        if ($this->rtl) {
            $x = $this->w + $dx;
        } else {
            $x = 0 + $dx;
        }
        $this->printTemplate($this->header_xobjid, $x, 0, 0, 0, '', '', false);
        if ($this->header_xobj_autoreset) {
            // reset header xobject template at each page
            $this->header_xobjid = false;
        }
    }

}

?>