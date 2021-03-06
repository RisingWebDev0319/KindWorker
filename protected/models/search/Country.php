<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Country as CountryModel;

/**
 * Country represents the model behind the search form about `app\models\Country`.
 */
class Country extends CountryModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'phonecode', 'type_id', 'state_id'], 'integer'],
        		[['sortname', 'name', 'created_by_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    public function beforeValidate(){
            return true;
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
    	$query = CountryModel::find()->alias('c')->joinWith('createdBy as cb');

		        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
						'defaultOrder' => [
								'id' => SORT_DESC
						]
				]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'c.id' => $this->id,
            'c.phonecode' => $this->phonecode,
            'c.type_id' => $this->type_id,
            'c.state_id' => $this->state_id,
           // 'created_by_id' => $this->created_by_id,
        ]);

        $query->andFilterWhere(['like', 'sortname', $this->sortname])
            ->andFilterWhere(['like', 'name', $this->name]);
        $query->FilterWhere(['like', 'cb.first_name', $this->created_by_id]);  
        return $dataProvider;
    }
}


