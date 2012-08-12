<?php
class Zend_View_Helper_FormLink extends Zend_View_Helper_FormElement
{
    public function formLink($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        // Render the button.
        $xhtml = '<a id="' . $this->view->escape($id) . '" '
            . $this->_htmlAttribs($attribs) . '>'
            . $value . '</a>';

        return $xhtml;
    }
}
