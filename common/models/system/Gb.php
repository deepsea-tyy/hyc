<?php

namespace common\models\system;

use Yii;

/**
 * This is the model class for table "sys_gb".
 *
 * @property int $id
 * @property string $name
 * @property string $gb_number 标准号
 * @property string $status
 * @property int $ics_id 分类
 * @property int $sampling 1采样
 * @property int $issue_time 发行时间
 * @property int $implementation_time 实施时间
 * @property string $code 标示
 * @property int $type 1强制标准2推荐标准
 * @property string $ccs 中国标准分类号
 * @property string $department 主管部门
 * @property string $unit 归口单位
 * @property string $issue_ unit 发行单位
 * @property string $remake 备注
 * @property int $created_at
 * @property int $updated_at
 */
class Gb extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_gb';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['ics_id', 'sampling', 'issue_time', 'implementation_time', 'type', 'created_at', 'updated_at'], 'integer'],
            [['name', 'code'], 'string', 'max' => 128],
            [['gb_number', 'ccs'], 'string', 'max' => 64],
            [['status', 'department', 'unit', 'issue_ unit', 'remake'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'gb_number' => 'gb_number',
            'status' => 'Status',
            'ics_id' => 'Ics ID',
            'sampling' => 'Sampling',
            'issue_time' => 'Issue Time',
            'implementation_time' => 'Implementation Time',
            'code' => 'Code',
            'type' => 'Type',
            'ccs' => 'Ccs',
            'department' => 'Department',
            'unit' => 'Unit',
            'issue_ unit' => 'Issue Unit',
            'remake' => 'Remake',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return GbQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GbQuery(get_called_class());
    }
}
