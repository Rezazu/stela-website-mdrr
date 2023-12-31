<?php
/**
 * Copyright (c) 2005-2008, Laurent Laville <pear@laurent-laville.org>
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the authors nor the names of its contributors
 *       may be used to endorse or promote products derived from this software
 *       without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PHP versions 4 and 5
 *
 * @category  HTML
 * @package   HTML_Progress2
 * @author    Laurent Laville <pear@laurent-laville.org>
 * @copyright 2005-2008 Laurent Laville
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   CVS: $Id: SmartyDynamic.php,v 1.9 2008/03/20 21:27:55 farell Exp $
 * @link      http://pear.php.net/package/HTML_Progress2
 * @since     File available since Release 2.0.0RC1
 */

require_once 'HTML/QuickForm/Renderer/Array.php';
require_once 'Smarty.class.php';

/**
 * The ActionDisplay class provides a dynamic form rendering
 * with Smarty template engine.
 *
 * @category  HTML
 * @package   HTML_Progress2
 * @author    Laurent Laville <pear@laurent-laville.org>
 * @copyright 2005-2008 Laurent Laville
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   Release: 2.4.0
 * @link      http://pear.php.net/package/HTML_Progress2
 * @since     Class available since Release 2.0.0RC1
 */

class ActionDisplay extends HTML_QuickForm_Action_Display
{
    /**
     * Style sheet for the custom layout
     *
     * @var    string
     * @access public
     * @since  2.1.0
     */
    var $css;

    /**
     * class constructor
     *
     * @param string $css custom stylesheet to apply, or default if not set
     *
     * @access public
     * @since  version 2.1.0 (2006-08-12)
     */
    function ActionDisplay($css = null)
    {
        // when no user-styles defined, used the default values
        $this->setStyleSheet($css);
    }

    /**
     * Outputs the form.
     *
     * @param object &$page the page being processed
     *
     * @return void
     * @access public
     * @since  version 2.0.0RC1 (2005-06-23)
     */
    function _renderForm(&$page)
    {
        // setup a template object
        $tpl               =& new Smarty();
        $tpl->template_dir = '.';
        $tpl->compile_dir  = 'cache/';

        $renderer =& new HTML_QuickForm_Renderer_Array(true);

        $styles = $this->getStyleSheet();
        $js     = '';

        // on preview tab, add progress bar javascript and stylesheet
        if ($page->getAttribute('id') == 'Preview') {
            $pb = $page->controller->createProgressBar();

            $styles .= $pb->getStyle();
            $js      = $pb->getScript();

            $pbElement =& $page->getElement('progressBar');
            $pbElement->setText($pb->toHtml() . '<br /><br />');
        }
        $page->accept($renderer);

        $tpl->assign(array('qf_style' => $styles, 'qf_script' => $js));
        $tpl->assign('form', $renderer->toArray());

        // capture the array stucture
        // (only for showing in sample template)
        ob_start();
        print_r($renderer->toArray());
        $tpl->assign('dynamic_array', ob_get_contents());
        ob_end_clean();

        $tpl->display('smarty-dynamic.tpl');
    }

    /**
     * Returns the custom style sheet to use for layout
     *
     * @param bool $content (optional) Either return css filename or string contents
     *
     * @return string
     * @access public
     * @since  version 2.1.0 (2006-08-12)
     */
    function getStyleSheet($content = true)
    {
        if ($content) {
            $styles = file_get_contents($this->css);
        } else {
            $styles = $this->css;
        }
        return $styles;
    }

    /**
     * Set the custom style sheet to use your own styles
     *
     * @param string $css (optional) File to read user-defined styles from
     *
     * @return bool    true if custom styles, false if default styles applied
     * @access public
     * @since  version 2.1.0 (2006-08-12)
     */
    function setStyleSheet($css = null)
    {
        // default stylesheet is into package data directory
        if (!isset($css)) {
            $this->css = 'C:\php5\pear\data' . DIRECTORY_SEPARATOR
                 . 'HTML_Progress2' . DIRECTORY_SEPARATOR
                 . 'smartydynamic.css';
        }

        $res = isset($css) && file_exists($css);
        if ($res) {
            $this->css = $css;
        }
        return $res;
    }
}
?>