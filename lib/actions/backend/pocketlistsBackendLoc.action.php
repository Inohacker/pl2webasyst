<?php

/**
 * A list of localized strings to use in JS.
 */
class pocketlistsBackendLocAction extends waViewAction
{
    public function execute()
    {
        $strings = array();

        // Application locale strings
        foreach (array(
                     'This to-do is assigned to another person. Are you sure you want to mark this item as complete?',
                     'ALL TO-DO LISTS AND ITEMS FROM THIS PROJECT WILL BE DELETED PERMANENTLY. ARE YOU SURE?',
                     'My personal settings',
                     'Close',
                     'None',
                     'Logbook',
                     'Delete',
                     'delete',
                     'Are you sure you want to delete this file?',
                     'This will permanently delete this comment. Are you sure?',
                     'Assigned to',
                     "Archiving the list will temporarily make this list and all it's items invisible. When you need this list in the future, simply unarchive it and continue from just where you stopped. Archive the list?",
                 ) as $s) {
            $strings[$s] = _w($s);
        }

        $n = 5;
        // plural forms hack
        $strings['Show all %d completed to-dos'] = str_replace($n, '%d', _w('Show all %d completed to-do', 'Show all %d completed to-dos', $n));

        $this->view->assign('strings',
            $strings ? $strings : new stdClass()); // stdClass is used to show {} instead of [] when there's no strings

        $this->getResponse()->addHeader('Content-Type', 'text/javascript; charset=utf-8');
    }
}
