<?php

class pocketlistsCommentAddAction extends waViewAction
{
    public function execute()
    {
        $item_id = waRequest::post('item_id', 0, waRequest::TYPE_INT);
        $comment = waRequest::post('comment', false, waRequest::TYPE_STRING_TRIM);

        if ($item_id && $comment != '') {
            $im = new pocketlistsItemModel();
            $item = $im->getById($item_id);
            if ($item) {

                if ($item['list_id'] && !pocketlistsRBAC::canAccessToList($item['list_id'])) {
                    $this->view->assign('error', array(
                        'code' => 403,
                        'message' => _w('Access denied')
                    ));
                    $this->setTemplate('templates/include/error.html');
                    return;
                }

                $cm = new pocketlistsCommentModel();

                $user = wa()->getUser();
                $insert_data = array(
                    'item_id'         => $item['id'],
                    'contact_id'      => $user->getId(),
                    'comment'         => $comment,
                    'create_datetime' => date('Y-m-d H:i:s'),
                );

                $last_id = $cm->insert($insert_data);
                if ($last_id) {
                    $comment = $cm->getById($last_id);
                    $comment['comment'] = pocketlistsNaturalInput::matchLinks($comment['comment']);

                    $comment = pocketlistsCommentModel::extendData($comment);

                    $this->logAction(pocketlistsLogAction::ITEM_COMMENT, array(
                        'list_id'    => $item['list_id'],
                        'comment_id' => $comment['id'],
                    ));

                    pocketlistsNotifications::notifyAboutNewComment($comment);

                    $this->view->assign('comment', $comment);
                } else {
//                    $this->errors = 'error while adding new item comment';
                }
            }
        }
        $this->setTemplate('templates/actions/comment/Comment.html');

    }
}
