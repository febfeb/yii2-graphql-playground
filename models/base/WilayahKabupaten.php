<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "wilayah_kabupaten".
 *
 * @property integer $id
 * @property integer $wilayah_propinsi_id
 * @property string $nama
 * @property string $ibukota
 * @property string $k_bsni
 *
 * @property \app\models\WilayahPropinsi $wilayahPropinsi
 * @property \app\models\WilayahKecamatan[] $wilayahKecamatans
 * @property string $aliasModel
 */
abstract class WilayahKabupaten extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wilayah_kabupaten';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wilayah_propinsi_id'], 'required'],
            [['wilayah_propinsi_id'], 'integer'],
            [['nama', 'ibukota'], 'string', 'max' => 100],
            [['k_bsni'], 'string', 'max' => 3],
            [['wilayah_propinsi_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\WilayahPropinsi::className(), 'targetAttribute' => ['wilayah_propinsi_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wilayah_propinsi_id' => 'Wilayah Propinsi ID',
            'nama' => 'Nama',
            'ibukota' => 'Ibukota',
            'k_bsni' => 'K Bsni',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWilayahPropinsi()
    {
        return $this->hasOne(\app\models\WilayahPropinsi::className(), ['id' => 'wilayah_propinsi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWilayahKecamatans()
    {
        return $this->hasMany(\app\models\WilayahKecamatan::className(), ['wilayah_kabupaten_id' => 'id']);
    }




}
