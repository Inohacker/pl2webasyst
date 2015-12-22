<?php

class pocketlistsItemCreateAction extends waViewAction
{
    public function execute()
    {
        $data = waRequest::post('data', false, waRequest::TYPE_ARRAY);
        $list_id = waRequest::post('list_id', false, waRequest::TYPE_INT);
        $assigned_contact_id = waRequest::post('assigned_contact_id', false, waRequest::TYPE_INT);

        $im = new pocketlistsItemModel();
        $inserted = $inserted_items = array();
        $user_id = wa()->getUser()->getId();
        // if no list_id -> add to inbox stream
        if ($assigned_contact_id) {
            $assign_contact = new waContact($assigned_contact_id);
            $us = new pocketlistsUserSettings($assign_contact->getId());
            $list_id = $us->getStreamInboxList();
        }
        if ($list_id && $data) {
            if (!is_array($data)) {
                $data = array($data);
            }
            $lm = new pocketlistsListModel();
            $list = $lm->getById($list_id);
            foreach ($data as $i => $d) {
                $data[$i]['create_datetime'] = date("Y-m-d H:i:s");
                $data[$i]['list_id'] = $list['id'];
                $data[$i]['contact_id'] = $user_id;

                if ($assigned_contact_id) {
                    $data[$i]['assigned_contact_id'] = $assign_contact->getId();
                    $data[$i]['due_date'] = waDateTime::date('Y-m-d');
                }

                $last_id = $im->insert($data[$i], 1);
                $inserted[] = $last_id;
                $inserted_items[] = $data[$i] + array('id' => $last_id);
            }
            pocketlistsNotifications::notifyAboutNewItems($inserted_items, $list);
        }
        if ($inserted) {
            $items = $im->getById($inserted);
            if (isset($items['id'])) {
                $items = array($items);
            }
        }

        $this->view->assign('items', $inserted ? $items : array());
    }
}