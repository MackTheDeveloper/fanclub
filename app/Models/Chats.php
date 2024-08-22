<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Chats extends Model
{
    use SoftDeletes;
    protected $table = 'chats';

    protected $fillable = ['sender_receiver', 'sender_id', 'receiver_id', 'message', 'read_by', 'deleted_by'];

    public static function addNew($data)
    {
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        $data['sender_id'] = $authId;
        $data['read_by'] = $authId;
        $data['sender_receiver'] = $authId . ',' . $data['receiver_id'];
        $allowed = ['sender_receiver', 'sender_id', 'receiver_id', 'message', 'read_by'];
        $data = array_intersect_key($data, array_flip($allowed));

        $chatAllowed = User::find($data['receiver_id']);
        if ($chatAllowed['allow_message'] == '1') {
            try {
                $create = new Chats();
                foreach ($data as $key => $value) {
                    $create->$key = $value;
                }
                $create->save();
                $return = $create;
            } catch (\Exception $e) {
                $return = $e->getMessage();
                $success = false;
            }
        }
        return ['data' => $return, 'success' => $success];
    }


    public static function retriveChat($sender_receiver, $limit = 30, $lastId = "", $refresh = 0)
    {

        $return = [];
        $authId = User::getLoggedInId();
        $srkey1 = implode(',', $sender_receiver);
        $srkey2 = implode(',', array_reverse($sender_receiver));
        $query = self::where(function ($q) use ($srkey1, $srkey2) {
            $q->where('sender_receiver', $srkey1)
                ->orWhere('sender_receiver', $srkey2);
        });
        if ($limit) {
            $query->limit($limit);
        }
        if ($lastId) {
            if ($refresh) {
                $query->where('id', '>', $lastId);
            } else {
                $query->where('id', '<', $lastId);
            }
        }
        $data = $query->whereRaw('(FIND_IN_SET(' . $authId . ',deleted_by) is null OR FIND_IN_SET(' . $authId . ',deleted_by) = 0)')->latest()->get()->reverse();
        if ($data) {
            $return = self::formatData($data);
        }
        return $return;
    }

    public static function formatData($data)
    {
        $return = $return1 = [];
        foreach ($data as $key => $value) {
            $string1 = date('YmdHi', strtotime($value->created_at)) . $value->sender_id;

            if (isset($return1[$string1])) {
                $return1[$string1]['lastId'] = $value->id;
                $return1[$string1]['message'][] = $value->message;
            } else {
                $single = [
                    "id" => $value->id,
                    "lastId" => $value->id,
                    "senderName" => User::getNameByIdForChat($value->sender_id),
                    "senderId" => $value->sender_id,
                    "senderIcon" => UserProfilePhoto::getProfilePhoto($value->sender_id),
                    "receiverId" => $value->receiver_id,
                    "message" => [$value->message],
                    "createdDate" => date('Y-m-d', strtotime($value->created_at)),
                    "createdAt" => $value->created_at,
                    "viewCreatedAt" => date('H:i A', strtotime($value->created_at)),
                ];
                $return1[$string1] = $single;
            }
        }
        foreach ($return1 as $key => $value) {
            $return[] = $value;
        }
        return $return;
    }


    public static function listChatPersons($userId, $sort = "", $search = "")
    {
        $return = [];
        $data = self::selectRaw('CASE WHEN `sender_id` = ' . $userId . ' THEN `receiver_id` ELSE `sender_id` END AS other, MAX(chats.id) AS latest, users.allow_message')
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->whereRaw('(FIND_IN_SET(' . $userId . ',deleted_by) is null OR FIND_IN_SET(' . $userId . ',deleted_by) = 0)')
            ->leftjoin('users', 'users.id', DB::raw('CASE WHEN `sender_id` = ' . $userId . ' THEN `receiver_id` ELSE `sender_id` END'))
            ->groupBy('other');
        if ($search) {
            $data->where('users.firstname', 'LIKE', '%' . $search . '%');
        }
        if ($sort) {
            if ($sort == 'time_desc') {
                $data->orderBy('latest', 'DESC');
            } else {
                $data->orderBy('latest', 'ASC');
            }
        } else {
            $data->orderBy('latest', 'DESC');
        }
        $data = $data->get();
        if ($data) {
            $return = self::formatChatsData($data);
        }
        return $return;
    }


    public static function formatChatsData($data)
    {
        $return = [];
        $authId = User::getLoggedInId();
        foreach ($data as $key => $value) {
            if (User::checkUserExist($value->other)) {
                $return[] = [
                    // "id"=>$value->id,
                    "chatWithId" => $value->other,
                    "refreshAfter" => 30,
                    "allowMessage" => $value->allow_message,
                    "chatWith" => User::getNameByIdForChat($value->other),
                    "personIcon" => UserProfilePhoto::getProfilePhoto($value->other),
                    "message" => self::getAttrById($value->latest, 'message'),
                    "isUnread" => (in_array($authId, explode(',', self::getAttrById($value->latest, 'read_by')))) ? '0' : '1',
                    "createdAt" => self::getAttrById($value->latest, 'created_at'),
                    "viewCreatedAt" => getFormatedDateForWeb(self::getAttrById($value->latest, 'created_at')),
                    // "viewCreatedAt" => getFormatedDate(self::getAttrById($value->latest,'created_at')),
                ];
            }
        }
        return $return;
    }

    public static function getAttrById($id, $column)
    {
        $return = "";
        $data = self::selectRaw($column)->where('id', $id)->first();
        if ($data) {
            $return = $data->$column;
        }
        return $return;
    }

    public static function clearChat($sender_receiver)
    {
        $return = [];
        $authId = User::getLoggedInId();
        $srkey1 = implode(',', $sender_receiver);
        $srkey2 = implode(',', array_reverse($sender_receiver));
        $key = array_search($authId, $sender_receiver);
        $otherId = ($key == "1") ? $sender_receiver[0] : $sender_receiver[1];

        $query = self::where(function ($q) use ($srkey1, $srkey2) {
            $q->where('sender_receiver', $srkey1)
                ->orWhere('sender_receiver', $srkey2);
        })->whereNull('deleted_by')->update(['deleted_by' => $authId]);

        $query = self::where(function ($q) use ($srkey1, $srkey2) {
            $q->where('sender_receiver', $srkey1)
                ->orWhere('sender_receiver', $srkey2);
        })->whereRaw('(FIND_IN_SET(' . $otherId . ',deleted_by) is null OR FIND_IN_SET(' . $otherId . ',deleted_by) = 0)')->whereNotNull('deleted_by')->update(['deleted_by' => $authId]);


        $query = self::where(function ($q) use ($srkey1, $srkey2) {
            $q->where('sender_receiver', $srkey1)
                ->orWhere('sender_receiver', $srkey2);
        })->whereRaw('(FIND_IN_SET(' . $authId . ',deleted_by) is null OR FIND_IN_SET(' . $authId . ',deleted_by) = 0)')->whereNotNull('deleted_by')->update(['deleted_by' => $srkey1]);

        return ['success' => true];
    }

    public static function readChat($sender_receiver)
    {
        $return = [];
        $authId = User::getLoggedInId();
        $srkey1 = implode(',', $sender_receiver);
        $srkey2 = implode(',', array_reverse($sender_receiver));
        $key = array_search($authId, $sender_receiver);
        $otherId = ($key == "1") ? $sender_receiver[0] : $sender_receiver[1];

        $query = self::where(function ($q) use ($srkey1, $srkey2) {
            $q->where('sender_receiver', $srkey1)
                ->orWhere('sender_receiver', $srkey2);
        })->whereNull('read_by')->update(['read_by' => $authId]);

        $query = self::where(function ($q) use ($srkey1, $srkey2) {
            $q->where('sender_receiver', $srkey1)
                ->orWhere('sender_receiver', $srkey2);
        })->whereRaw('(FIND_IN_SET(' . $otherId . ',read_by) is null OR FIND_IN_SET(' . $otherId . ',read_by) = 0)')->whereNotNull('read_by')->update(['read_by' => $authId]);


        $query = self::where(function ($q) use ($srkey1, $srkey2) {
            $q->where('sender_receiver', $srkey1)
                ->orWhere('sender_receiver', $srkey2);
        })->whereRaw('(FIND_IN_SET(' . $authId . ',read_by) is null OR FIND_IN_SET(' . $authId . ',read_by) = 0)')->whereNotNull('read_by')->update(['read_by' => $srkey1]);

        return ['success' => true];
    }


    public static function listChatPersonsUnread()
    {
        $authId = User::getLoggedInId();
        $return = [];
        if ($authId) {
            $data = self::selectRaw('CASE WHEN `sender_id` = ' . $authId . ' THEN `receiver_id` ELSE `sender_id` END AS other, MAX(chats.id) AS latest')
                ->where(function ($q) use ($authId) {
                    $q->where('sender_id', $authId)
                        ->orWhere('receiver_id', $authId);
                })
                ->whereRaw('(FIND_IN_SET(' . $authId . ',deleted_by) is null OR FIND_IN_SET(' . $authId . ',deleted_by) = 0)')
                ->whereRaw('(FIND_IN_SET(' . $authId . ',read_by) is null OR FIND_IN_SET(' . $authId . ',read_by) = 0)')
                ->groupBy('other');
            $return = $data->get();
        }
        return count($return);
    }
}
