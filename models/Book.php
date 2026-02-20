<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Book extends ActiveRecord
{
    public $imageFile = null;
    public $author_ids = [];

    public static function tableName(): string
    {
        return '{{%books}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules(): array
    {
        return [
            [['title', 'year'], 'required'],
            ['title', 'string', 'max' => 255],
            ['year', 'integer', 'min' => 1000, 'max' => 9999],
            ['description', 'string'],
            ['isbn', 'string', 'max' => 20],
            ['cover_image', 'string', 'max' => 255],
            ['author_ids', 'safe'],
            ['imageFile', 'file', 'extensions' => 'jpg, jpeg, png, gif', 'skipOnEmpty' => true, 'checkExtensionByMimeType' => false],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'year' => 'Year',
            'description' => 'Description',
            'isbn' => 'ISBN',
            'cover_image' => 'Cover Image',
            'imageFile' => 'Cover Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%book_author}}', ['book_id' => 'id']);
    }

    public function getAuthorIds(): array
    {
        return $this->getAuthors()->select('id')->column();
    }

    public function saveAuthors($authorIds): void
    {
        $authorIds = array_filter(array_map('intval', (array)$authorIds));
        \Yii::$app->db->createCommand()->delete('{{%book_author}}', ['book_id' => $this->id])->execute();
        if (!empty($authorIds)) {
            $rows = [];
            foreach ($authorIds as $authorId) {
                $rows[] = [$this->id, (int)$authorId];
            }
            \Yii::$app->db->createCommand()->batchInsert('{{%book_author}}', ['book_id', 'author_id'], $rows)->execute();
        }
    }
}
