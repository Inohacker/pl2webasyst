<?php

class pocketlistsRightConfig extends waRightConfig
{
    const RIGHT_NONE = 0;
    const RIGHT_FULL = 1;

    public function init()
    {
        $this->addItem('canassign', _w('Can assign to-dos to teammates'), 'checkbox');

        $list_model = new pocketlistsListModel();
        $items = array();
        // todo: только активные? или все подряд?
        foreach ($list_model->getAllActiveLists() as $list) {
            $items[$list['id']] = $list['name'];
        }
        $this->addItem(
            'list',
            _w('Lists'),
            'selectlist',
            array(
                'items' => $items,
                'position' => 'right',
                'options' => array(
                    self::RIGHT_NONE => _w('No access'),
                    self::RIGHT_FULL => _w('Full access'),
                ),
            )
        );
    }

    public function setDefaultRights($contact_id)
    {
        return array('canassign' => 1);
    }
}
