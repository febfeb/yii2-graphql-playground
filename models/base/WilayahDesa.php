<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "wilayah_desa".
 *
 * @property integer $id
 * @property integer $wilayah_kecamatan_id
 * @property string $nama
 * @property string $kodepos
 *
 * @property \app\models\WilayahKecamatan $wilayahKecamatan
 * @property string $aliasModel
 */
abstract class WilayahDesa extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wilayah_desa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wilayah_kecamatan_id'], 'required'],
            [['wilayah_kecamatan_id'], 'integer'],
            [['nama'], 'string', 'max' => 100],
            [['kodepos'], 'string', 'max' => 5],
            [['wilayah_kecamatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\WilayahKecamatan::className(), 'targetAttribute' => ['wilayah_kecamatan_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wilayah_kecamatan_id' => 'Wilayah Kecamatan ID',
            'nama' => 'Nama',
            'kodepos' => 'Kodepos',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWilayahKecamatan()
    {
        return $this->hasOne(\app\models\WilayahKecamatan::className(), ['id' => 'wilayah_kecamatan_id']);
    }




}
