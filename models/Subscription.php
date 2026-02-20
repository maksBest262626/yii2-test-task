<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Subscription extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%subscriptions}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false, // subscriptions table has no updated_at column
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['author_id', 'phone'], 'required'],
            ['author_id', 'integer'],
            ['phone', 'string', 'max' => 20],
            ['phone', 'match', 'pattern' => '/^\+?[0-9]{7,15}$/', 'message' => 'Enter a valid phone number (digits only, 7-15 chars, optional leading +)'],
            ['author_id', 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
            ['phone', 'unique', 'targetAttribute' => ['author_id', 'phone'], 'message' => 'This phone number is already subscribed to this author.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author',
            'phone' => 'Phone',
            'created_at' => 'Created At',
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}