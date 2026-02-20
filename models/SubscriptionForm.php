<?php

namespace app\models;

use yii\base\Model;

class SubscriptionForm extends Model
{
    public ?int $author_id = null;
    public string $phone = '';

    public function rules(): array
    {
        return [
            [['author_id', 'phone'], 'required'],
            ['author_id', 'integer'],
            ['phone', 'string', 'max' => 20],
            ['phone', 'match', 'pattern' => '/^\+?[0-9]{7,15}$/', 'message' => 'Enter a valid phone number (digits only, 7-15 chars, optional leading +)'],
            ['author_id', 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'author_id' => 'Author',
            'phone' => 'Phone Number',
        ];
    }
}
