<?php
/**
 * Created by PhpStorm.
 * User: Mikkle
 * Q Q:776329498
 * Date: 2017/8/21/021
 * Time: 22:17
 */

namespace app\base\model;


use app\base\controller\RedisHash;
use think\Exception;
use think\Loader;
use think\Log;

class BaseHash extends Base
{

    protected $hashKey;
    protected $hashKeyValue;
    public $RedisHash;
    public function __construct($data = [])
    {
        parent::__construct($data);
        try{
            $this->RedisHash = RedisHash::instance()->setTable($this->table);
        }catch (Exception $e){
            Log::error($e->getMessage());
            $this->RedisHash= false;
        }

        if (!isset($this->hashKey)){
            if ($this->hasColumn("guid")){
                $this->hashKey="guid";
            }
        }
    }

    /**
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $key
     * @param array $field
     * @param array $data
     * @return mixed
     */
    public static function quickGet($key,$field=[],$data = [])
    {
        $hash = new static($data);
        try{
            if ($hash->RedisHash==false){
                throw new Exception("Redis no away!call Mikkle please");
            }
            $return_data = $hash->getHashByKey($key,$field);
            if ($return_data==null){
                $hash_key = $hash->getHashKey();
                $data = $hash->where([$hash_key=>$key])->find();
                if ($data){
                    $data->RedisHash->setKey($key)->set($data);
                }
                $return_data= $hash->getHashByKey($key,$field);
            }
            return $return_data;
        }catch (Exception $e){
            Log::error($e->getMessage());
            $hash_key = $hash->getHashKey();
            return $hash->where([$hash_key=>$key])->find();
        }


    }

    protected function getHashByKey($key, $field = [])
    {
        try {

            return $this->RedisHash->setKey($key)->get($field);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    protected function getHashKey(){
        $this->hashKey = isset($this->hashKey) ? $this->hashKey : $this->getPk();
        return $this->hashKey;
    }

    protected function getHashKeyValue(BaseHash $hash)
    {

        $hash_key = $hash->getHashKey();
        $pk = $hash->getPk();
        $hash_data = $hash->getData();
        try {
            switch (true) {
                case ($hash->RedisHash==false):
                    return false;
                    break;
                case (empty($hash_data)):
                    return false;
                    break;
                case (empty($hash->updateWhere)):
                    switch (true) {
                        case (isset($hash_data[$hash_key])):
                            switch (true) {
                                case (is_string($hash_data[$hash_key]) || is_numeric($hash_data[$hash_key])):
                                    return $hash_data[$hash_key];
                                    break;
                                default:
                                    return false;
                            }
                            break;
                        case(isset($hash_data[$pk])):
                            switch (true) {
                                case (is_string($hash_data[$pk]) || is_numeric($hash_data[$pk])):
                                    return $hash_data[$pk];
                                    break;
                                default:
                                    return false;
                            }
                            break;
                        default:
                            return false;
                    }
                    break;
                case (isset($hash->updateWhere[$hash_key])):
                    switch (true) {
                        case (is_string($hash->updateWhere[$hash_key]) || is_numeric($hash->updateWhere[$hash_key])):
                            return $hash->updateWhere[$hash_key];
                            break;
                        case (is_array($hash->updateWhere[$hash_key])):
                            return $hash->where($hash->updateWhere)->column($hash_key);
                            break;
                        default:
                            return false;
                    }
                    break;
                case (!isset($hash->updateWhere[$hash_key]) && $hash_key != $pk):
                    return $hash->where($hash->updateWhere)->column($hash_key);
                    break;

                case(!isset($hash->updateWhere[$hash_key]) && $hash_key == $pk):
                    switch (true) {
                        case (is_string($hash->updateWhere[$hash_key]) || is_numeric($hash->updateWhere[$hash_key])):
                            return $hash->updateWhere[$hash_key];
                            break;
                        case (is_array($hash->updateWhere[$hash_key])):
                            return $hash->where($hash->updateWhere)->column($hash_key);
                            break;
                        default:
                            return false;
                    }
                    break;
                default :
                    return false;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

    }


    static protected function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        self::event('after_insert', function (BaseHash $model) {
            $model->insertHash($model);
        });
        self::event('after_update', function (BaseHash $model) {
            $model->updateHash($model);
        });

        self::event('after_delete', function (BaseHash $model) {
            $model->deleteHash($model);
        });
    }

    static protected function deleteHash(BaseHash $hash){
        if (empty($hash->getData())) return false;
        try{
            $hash_key_value = $hash->getHashKeyValue($hash);
            $hash->RedisHash->setKey($hash_key_value)->deleteByKey();
        }catch (Exception $e){
            Log::error($e->getMessage());
            return false;
        }


    }

    static protected function insertHash(BaseHash $hash){
        try {
            if ($hash->RedisHash==false){
                throw new Exception("Redis no away!call Mikkle please");
            }
            switch (true) {
                case(empty($hash->getData())):
                    return false;
                    break;
                case($hash->isUpdate):
                    $hash_key_value = $hash->getHashKeyValue($hash);
                    switch (true) {
                        case (is_string($hash_key_value) || is_numeric($hash_key_value)):

                            $hash->RedisHash->setKey($hash_key_value)->set($hash->getData());
                            break;
                        case (is_array($hash_key_value)):
                            foreach ($hash_key_value as $value) {
                                $hash->RedisHash->setKey($value)->set($hash->getData());
                            }
                            break;
                        default:
                            return false;
                    }
                    break;
                default :
                    return false;
                    break;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    static protected function updateHash(BaseHash $hash)
    {
        try {
            if ($hash->RedisHash==false){
                throw new Exception("Redis no away!call Mikkle please");
            }
            switch (true) {
                case(empty($hash->getData())):
                    return false;
                    break;
                case($hash->isUpdate):
                    $hash_key_value = $hash->getHashKeyValue($hash);
                    switch (true) {
                        case (is_string($hash_key_value) || is_numeric($hash_key_value)):
                            if ($hash->RedisHash->exists($hash_key_value)) {
                                $hash->RedisHash->setKey($hash_key_value)->set($hash->getData());
                            }
                            break;
                        case (is_array($hash_key_value)):
                            foreach ($hash_key_value as $value) {
                                if ($hash->RedisHash->exists($value)) {
                                    $hash->RedisHash->setKey($value)->set($hash->getData());
                                }
                            }
                            break;
                        default:
                            return false;
                    }
                    break;
                default :
                    return false;
                    break;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }


}