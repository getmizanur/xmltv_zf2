<?php

namespace Mm\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Controller\AbstractController as BaseController;

class IoDocs extends AbstractHelper
{
    public function __invoke(array $types)
    {
        $counter = 0;

        $message = null; 
        foreach ($types as $ktype => $endpoints) {
            $message .= "<h4>$ktype</h4>";
            $message .= '<div class="accordion" id="accordion-' . $counter . '">';
            foreach($endpoints as $kendpoint => $endpoint) {
                $background = '';
                if(strtolower($endpoint['methods']['HttpMethod']) == 'post') {
                    $background = 'style="background:#44a520"';
                }else if(strtolower($endpoint['methods']['HttpMethod']) == 'put') {
                    $background = 'style="background:#F16623"';
                }else if(strtolower($endpoint['methods']['HttpMethod']) == 'delete') {
                    $background = 'style="background:#ff2019"';
                }

                $message .= '<div class="accordion-group">';
                $message .= '<div class="accordion-heading">';
                $message .= '<a class="accordion-toggle" style="width:100%;margin:0px;padding:0px" ' 
                    . 'data-toggle="collapse" data-parent="#accordion2" href="#collapse' 
                    . $counter . '"><font class="icon-home" ' . $background . '>'
                    . strtoupper($endpoint['methods']['HttpMethod']) 
                    . '</font><font class="icon-header">' 
                    . '<b style="color:black">' 
                    . $endpoint['methods']['MethodName'] . '</b>&nbsp;&nbsp;&nbsp;'
                    . $endpoint['methods']['URI'] . '</font></a></div>';
                $message .= '<div id="collapse' . $counter 
                    . '" class="accordion-body collapse">'
                    . '<div class="accordion-inner">';
                $message .= '<form style="display: block" id="'. $kendpoint . '" '
                    . 'method="' . strtoupper($endpoint['methods']['HttpMethod']) . '" ' 
                    . 'action="' . $endpoint['methods']['URI'] . '">';
                $message .= '<table class="parameters">'
                    . '<caption>' . $endpoint['methods']['Synopsis'] . '</caption>'
                    . '<thead>'
                    . '<th scope="col">Parameter</th>'
                    . '<th scope="col">Value</th>'
                    . '<th scope="col">Type</th>'
                    . '<th align="col">Description</th>'
                    . '</thead>'
                    . '<tbody>';
                foreach($endpoint['methods']['parameters'] as $kparam => $parameter) {
                    $message .= '<tr class=' 
                        . ((strtolower($parameter['Required']) == 'y') ? '"required"' : '"table-query"') . '>'
                        . '<td>' . $kparam . '</td>'
                        . '<td><input type="text" name="' . $kparam . '" id="'. $kendpoint . '-' 
                        . str_replace(':', 'c', $kparam)
                        . '" value="'. $parameter['Default'] . '" ' 
                        . 'class=' . ((strtolower($parameter['Required']) == 'y') ? '"required"' : '"optional"') . '></td>'
                        . '<td>' . $parameter['Type'] . '</td>'
                        . '<td>' . $parameter['Description'] . '</td>'
                        . '</tr>';
                    $counter++;
                } 

                $message .= '</tbody>'
                    . '</table><br>'
                    . '<input type="submit" id="submit" name="Try it!" value="Try it!">&nbsp;&nbsp;&nbsp;'
                    . '<a id="' . $kendpoint . '-clear-result" href="#" '
                    . 'style="visibility:hidden;color:red">Clear Result</a>'
                    . '</form>'
                    . '<div id="' . $kendpoint . '-result-uri"></div> '
                    . '<div id="' . $kendpoint . '-result-headers"></div> '
                    . '<div id="' . $kendpoint . '-result-response"></div> '
                    . '</div>'
                    . '</div>'
                    . '</div>';
            }         

            $message .= '</div>';
        }

        $message .= $this->javaScript($types);

        return $message;
    }

    public function javaScript($types)
    {
        $counter = 0;

        $message = null; 
        foreach ($types as $ktype => $endpoints) {
            foreach($endpoints as $kendpoint => $endpoint) {
                $message .= '<script type="text/javaScript">';
                $message .= '$(document).ready(function(){'
                    . '$("#' . $kendpoint . '-clear-result").click(function() { '
                    . '$("#' . $kendpoint . '-result-uri").html(""); '
                    . '$("#' . $kendpoint . '-result-response").html(""); '
                    . '$("#' . $kendpoint . '-result-headers").html(""); '
                    . '$(this).attr("style", "visibility: hidden"); '
                    . '});'
                    . '$("#' . $kendpoint . '").submit(function() { '
                    . 'var values = $(this).serializeArray(); '
                    . 'var uri = "' . $endpoint['methods']['URI'] . '"; '
                    . 'uri = uri.replace(/:/g, "c"); ' 
                    . 'var error = false; '
                    . 'for(var i=0; i<values.length; i++) { '
                    . 'var regex = new RegExp(values[i].name.replace(/:/, "c")); '
                    . 'if(regex.test(uri)){ '
                    . 'if(values[i].value != ""){ '
                    . 'uri = uri.replace(values[i].name.replace(/:/, "c"), values[i].value); '
                    . '$("#' . $kendpoint . '-" + values[i].name.replace(/:/, "c")).attr("style", ""); ' 
                    . '}else{ '
                    . 'if($("#' . $kendpoint . '-" + values[i].name.replace(":", "c")).attr("class").match(/required/) != null) {'
                    . 'var name = values[i].name.replace(":", "c"); '
                    . '$("#' . $kendpoint . '-" + name).attr("style", "border:1px solid red"); ' 
                    . 'error = true; '
                    . '}else{'
                    . 'var key = values[i].name.replace(":", ""); '
                    . 'var name = values[i].name.replace(":", "c"); '
                    . 'var field = (key + "=" + name); '
                    . 'uri = uri.replace(field, ""); '
                    . 'uri = uri.replace(/&$/, ""); '
                    . '} '
                    . '} '
                    . '} '
                    . '} '
                    . 'if(error == false) { '
                    . '$.ajax({ '
                    . 'url: uri, '
                    . 'type: "' . $endpoint['methods']['HttpMethod'] . '", '
                    . 'success: function(result, status, xhr) { '
                    . '$("#' . $kendpoint . '-result-uri").html("<h5>Request URI</h5><pre>" + '
                    . '"http://' . $_SERVER['SERVER_NAME'] . '" + uri + "</pre>"); '
                    //. '"http://local.mme-jonah" + uri + "</pre>"); '
                    . '$("#' . $kendpoint . '-result-response").html("<h5>Response</h5><pre>" + JSON.stringify(result, null, 4) + "</pre>"); '
                    . '$("#' . $kendpoint . '-result-headers").html("<h5>Response Headers</h5><pre>" + JSON.stringify((xhr.getAllResponseHeaders().trim().split(/\r\n|\r|\n/g)), null, 4) + "</pre>"); '
                    . '$("#' . $kendpoint . '-clear-result").attr("style", "visibility: ture; color:red;")'
                    . '} '
                    . '}); '
                    . '}'
                    . 'return false; '
                    . '}) '
                    . '}) ';
                $message .= "</script>";
            }
        }

        return $message;
    }
}
