<?php

class pocketlistsHelper
{
    public static function getUserSettings()
    {
        $cs = new waContactSettingsModel();
        $app_name = wa()->getApp();
        $settings = $cs->get(wa()->getUser()->getId(), $app_name);

        return array(
            'app_icon' => isset($settings['app_icon']) ? $settings['app_icon'] : 0,
            'email_me' => json_decode(isset($settings['email_me']) ? $settings['email_me'] : "", true),
            'daily_recap' => json_decode(isset($settings['daily_recap']) ? $settings['daily_recap'] : "", true)
        );
    }

    public static function getAccessContacts($pocket_id)
    {
        $wcr = new waContactRightsModel();
        $query = "SELECT DISTINCT
                group_id
            FROM wa_contact_rights
            WHERE
              (app_id = 'wa()->getApp()' AND ((name = s:id AND value = 1) OR (name = 'backend' AND value = 2))
              OR
              (app_id = 'webasyst' AND name = 'backend' AND value = 1))";
        $contact_ids = $wcr->query($query,
            array(
                'id' => 'pocket.'.$pocket_id
            ))->fetchAll();
        $contacts = array();
        foreach($contact_ids as $id) {
            $contact = new waContact(-$id['group_id']);
            $contacts[$contact->getId()] = $contact->getName();
        }
        return $contacts;
    }
}