<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SupportMessage
 *
 * @property int $id
 * @property int $chat_id
 * @property int $message_id
 * @property int|null $update_id
 * @property int $author
 * @property int $user
 * @property int|null $reply_to_message_id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage whereReplyToMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage whereUpdateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessage whereUser($value)
 * @mixin \Eloquent
 */
class SupportMessage extends Model
{
    protected $fillable = [
        'message_id', 'update_id', 'author', 'user', 'reply_to_message_id', 'text', 'chat_id'
    ];
}
