<?php
namespace SK\TradeModule\Model;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "taders_sent".
 *
 * @property integer $trader_id
 * @property resource $ip_addr
 * @property string $created_at
 */
class TraderSent extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'taders_sent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trader_id'], 'integer'],
            [['ip_addr'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * Поиск в таблице по ипу
     *
     * @param string $ip
     * @return array
     */
    public static function findByIp($ipAddr)
    {
        $packedIp = \inet_pton($ipAddr);

        return static::find()
            ->where(['ip_addr' => $packedIp])
            ->all();
    }

    /**
     * После поиска автоматически преобразует IP пользователя в читаемый формат.
     *
     * @return void
     */
    public function afterFind()
    {
         $ipAddr = @\inet_ntop($this->ip_addr);

         if (false !== $ipAddr) {
             $this->ip_addr = $ipAddr;
         }

        parent::afterFind();
    }

    /**
     * Перед записью в базу автоматически конвертирует IP адрес пользователя в упакованное in_addr представление.
     *
     * @param bool $insert Whether this method called while inserting a record. If false, it means the method is called while updating a record.
     * @return bool Whether the insertion or updating should continue. If false, the insertion or updating will be cancelled.
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->ip_addr = \inet_pton($this->ip_addr);

        return true;
    }
}
