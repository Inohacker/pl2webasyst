<?php

class pocketlistsListUpdateController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('id', false, waRequest::TYPE_INT);
        $data = waRequest::post('data', false, waRequest::TYPE_ARRAY);

        $lm = new pocketlistsListModel();
        if ($list_id > 0) {
            $data['id'] = $list_id;
        } else {
            $data['create_datetime'] = date("Y-m-d H:i:s");
        }
        $data['contact_id'] = wa()->getUser()->getId();

        $list_icons = pocketlistsHelper::getListIcons();
        $matched_icon = pocketlistsNaturalInput::matchCategory($data['name']);
        if ($matched_icon && isset($list_icons[$matched_icon])) {
            $data['icon'] = $list_icons[$matched_icon];
        }

        $data = $lm->add($data, 1);
        if ($data) {
            // log this action
            $this->logAction('list_created', $data);
            pocketlistsNotifications::notifyAboutNewList($data);
        }

        $this->response = array('id' => $data['id']);
    }
}
