<?php

namespace Walleye;

/**
 * A helper class to generate tables
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @package Walleye
 */
class Table
{
    /**
     *
     * Pass this function data as an array with some optional options
     * to create a basic table
     *
     * @static
     * @param array $data
     * @param array $options
     * @return string
     */
    public static function generate($data, $options = array())
    {
        $table_options = array(
            'id' => (isset($options['id'])) ? $options['id'] : '',
            'column_names' => (isset($options['column_names']))
                    ? $options['column_names'] : array(),
            'include_pager' => (isset($options['include_pager']))
                    ? $options['include_pager'] : false
        );

        $id = ($table_options['id'] != '')
                ? ' id="' . $table_options['id'] . '"' : '';

        $html = '<table' . $id . '>';

        // thead
        $html .= '<thead>';
        $html .= '<tr>';
        foreach ($table_options['column_names'] as $name) {
            $html .= "<th>$name</th>";
        }
        $html .= '</tr>';
        $html .= '</thead>';

        //tfoot
        $html .= '<tfoot>';
        $html .= '</tfoot>';

        //tbody
        $html .= '<tbody>';
        foreach ($data as $row) {
            $onclick = ($row['onclick'] != '')
                    ? ' onclick="document.location=\'' . $row['onclick'] . '\'"'
                    : '';
            $html .= '<tr' . $onclick . '>';
            for ($i = 0; $i < count($table_options['column_names']); $i++) {
                $html .= '<td>' . $row[$table_options['column_names'][$i]] . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';

        //pager
        if ($table_options['include_pager'] == true) {
            $html .= '<div>';
            $html .= '<a title="First Page" href="#">';
            $html .= '</a>';
            $html .= '<a title="Previous Page" href="#">';
            $html .= '</a>';
            $html .= '<input type="text" />';
            $html .= '<a title="Next Page" href="#">';
            $html .= '</a>';
            $html .= '<a title="Last Page" href="#">';
            $html .= '</a>';
            $html .= '<select>';
            $html .= '<option value="10" selected="selected">10 results</option>';
            $html .= '<option value="20">20 results</option>';
            $html .= '<option value="30">30 results</option>';
            $html .= '<option value="40">40 results</option>';
            $html .= '</select>';
            $html .= '</div>';
        }

        return $html;
    }
}

/* End of file */
